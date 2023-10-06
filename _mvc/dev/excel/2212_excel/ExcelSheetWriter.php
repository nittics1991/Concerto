<?php

namespace Concerto\excel;

use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;
use Concerto\excel\ExcelSheet

class ExcelBook
{
    private const TEMP_NAME_PREFIX = '';

    private array $escapeStrings = [
        ['&', '&amp;'],
        ['<', '&lt;'],
        ['>', '&gt;'],
        ['"', 'quot;'],
        ["'", '&apos;'],
    ];

    private int $bufferLimit;

    /**
    *   @var ?resource
    */
    private ?resource $fp;

    public function __construct(
        int $bufferLimit = 8191,
    ) {
       $this->setBufferLimit(
            $bufferLimit,
        );
    }

    public function __destruct(
    ) {
       if (isset($fp)) {
           $this->close();
       }
    }

    public function setBufferLimit(
        int $bufferLimit,
    ):static
    {
        if ($bufferLimit < 0) {
            throw new InvalidArgumentException(
                "temp file name create error",
            );
        }

        $this->bufferLimit = $bufferLimit;

        return $this;
    }

    public function output(
        ExcelSheet $sheet,
    ):string
    {
        $filename = $this->createTempFileName();

        $this->fp = $this->open($filename);

        $this->writeHeader($sheet);
        
        $this->writeSheetData($sheet);

        $this->writeFooter();
        
        $this->close();

        return $filename;
    }

    private function createTempFileName():string
    {
        $sheetTempFileName = tempnam(
            sys_get_temp_dir(),
            static::TEMP_NAME_PREFIX
        );

        if ($sheetTempFileName === false) {
            throw new RuntimeException(
                "temp file name create error",
            );
        }

        return $sheetTempFileName;
    }

    private function open(
        string $filename,
    ):resource
    {
        $fp = fopen(
            $filename,
            'w',
        );

        if ($fp === false) {
            throw new RuntimeException(
                "stream open error:{$filename}",
            );
        }

        return $fp;
    }

    private function close(
        resource $fp,
    ):void
    {
        $isClosed = fclose($fp);

        $this->fp = null;

        if ($isClosed === false) {
            throw new RuntimeException(
                "stream close error:{$filename}",
            );
        }
    }

    private function writeSheetData(
        ExcelSheet $sheet,
    ):void
    {
        $buffer = '';

        foreach($sheet as $rowNo => $columns) {
            $buffer = $this->convertRowToString(
                $rowNo,
                $columns,
            );
            
            if (strlen($buffer) > $this->bufferLimit) {
                $cunks = str_split($buffer, $this->bufferLimit);

                $buffer = $this->flushChunkedData(
                    $cunks,
                );
            }
        }
        
        if (strlen($buffer) > 0) {
            $this->flush($buffer);
        }
    }

    private function convertRowToString(
        int $rowNo,
        array $columns,
    ):string {
        $buffer = $this->startRowString($rowNo);

        foreach($columns as $columnNo => $value) {
            $buffer .= $this->columnString(
                $rowNo,
                $columnNo,
                $value,
            );
        }

        $buffer .= $this->endRowString();

        return $buffer;
    }

    private function startRowString(
        int $rowNo,
    ):string {
        return '<row r="' . $rowNo . '">' . PHP_EOL;
    }

    private function endRowString():string
    {
        return '</row>' . PHP_EOL;
    }

    private function columnString(
        int $rowNo,
        int $columnNo,
        mixed $value,
    ):string {
        $type = gettype($value);

        if ($type === 'integer' || $type === 'double') {
            return $this->numberValue(
                $rowNo,
                $columnNo,
                $value,
            ) . PHP_EOL;
        }

        if ($type === 'string' || $type === 'null') {
            return $this->stringValue(
                $rowNo,
                $columnNo,
                $value,
            ) . PHP_EOL;
        }

        if (
            $type === 'object' &&
            $value instanceof DateTimeInterface
        ) {
            return $this->dateValue(
                $rowNo,
                $columnNo,
                $value,
            ) . PHP_EOL;
        }

        throw new RuntimeException(
            "cell data type error. row:{$rowNo} column:{$columnNo}"
        );
    }
    
    private function numberValue(
        int $rowNo,
        int $columnNo,
        int|float $value,
    ):string {
        return '<c><v>' . strval($value) .'</v></c>';
    }
    
    private function stringValue(
        int $rowNo,
        int $columnNo,
        ?string $value,
    ):string {
        return mb_substr($value, 0, 1) === '='?
            '<c><f>' . strval($value) . '</f></c>';
            '<c t="inlineStr"><is><t>' .
                $this->escapeString($value) .
                '</t></is></c>';
    }
    
    private function escapeString(
        ?string $value,
    ):string {
        $escaped = strval($value);

        foreach ($this->escapeStrings as $list) {
            $escaped = mb_ereg_replace(
                $list[0],
                $list[0],
                $escaped,
            );

            if ($escaped === false || $escaped === false) {
                throw new RuntimeException(
                    "string replace error:{$value}",
                );
            }
        }

        return $escaped;
    }
    
    private function dateValue(
        int $rowNo,
        int $columnNo,
        DateTimeInterface $value,
    ):string {
        return '<c s="14"><v>' .
            $this->convertDateFormat($value) .
            '</v></c>';
    }
    
    private function convertDateFormat(
        DateTimeInterface $value,
    ):string {
            $year = $date->format('Y');
            $month = $date->format('m');
            $day = $date->format('d');
            $hours = $date->format('H');
            $minutes = $date->format('i');
            $seconds = $date->format('s');

            $excel1900isLeapYear = $year == 1900 && $month <= 2?
                false:true;

            $excelBaseDate = 2415020;

            if ($month > 2) {
                $month -= 3;
            } else {
                $month += 9;
                $year -= 1;
            }

            //Calculate the Julian Date, then subtract the Excel base date
            //JD 2415020 = 31-Dec-1899 Giving Excel Date of 0

            $century = substr($year,0,2);
            $decade = substr($year,2,2);

            $excelDate = floor((146097 * $century) / 4) +
                floor((1461 * $decade) / 4) +
                floor((153 * $month + 2) / 5) +
                $day +
                1721119 - $excelBaseDate + $excel1900isLeapYear;

            $excelTime = ($hours * 3600 + $minutes * 60 + $seconds) / 86400;

            return strval($excelDate + $excelTime);
    }
    
    private function flushChunkedData(
        array $cunks,
    ):string {
        $lastChunkData = array_pop($cunks);

        foreach((array)$cunks as $chunk) {
            $this->flush($chunk);
        }

        return $lastChunkData;
    }

    private function flush(
        string $cunk,
    ):void {
        $writedLength = fwrite(
            $this->fp,
            $cunk,
        );

        if ($writedLength === false) {
            throw new RuntimeException(
                "stream write error:{$cunk}",
            );
        }
    }

    private function writeHeader(
        ExcelSheet $sheet,
    ):void
    {
        $buffer = implode(
            PHP_EOL,
            [
                '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
                implode [
                    ' ',
                    [
                        '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"',
                        'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"',
                        'xmlns:xr="http://schemas.microsoft.com/office/spreadsheetml/2014/revision"',
                        'xr:uid="{00000000-0001-0000-0000-000000000000}"',
                    ],
                ),
                '<dimension ref="' . $sheet->rangeString() . '"/>',
                '<sheetData>',
            ],
        );

        $this->flushAll($buffer);
    }

    private function writeFooter()
    {
        $buffer = implode(
            PHP_EOL,
            [
                '</sheetData>',
                '</worksheet>',
            ],
        );

        $this->flushAll($buffer);
    }

    private function flushAll(
        string $buffer,
    ):void {
        $cunks = str_split($buffer, $this->bufferLimit);

        $buffer = $this->flushChunkedData(
            $cunks,
        );
        
        $this->flush($buffer);
    }



}
