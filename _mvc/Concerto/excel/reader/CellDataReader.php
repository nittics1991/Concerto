<?php

/**
*   CellDataReader
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\reader;

use DateTimeImmutable;
use RuntimeException;
use Concerto\excel\{
    ExcelAddress,
    ExcelArchive,
    ExcelSheet,
};
use Concerto\excel\parts\{
    ExcelContents,
    ExcelNode,
    SharedStrings,
    SheetParts,
};

class CellDataReader
{
    /**
    *   @var ExcelArchive
    */
    protected ExcelArchive $excelArchive;

    /**
    *   @var ExcelContents
    */
    protected ExcelContents $excelContents;

    /**
    *   @var SharedStrings
    */
    protected SharedStrings $sharedStrings;

    /**
    *   __construct
    *
    *   @param ExcelArchive $excelArchive
    */
    public function __construct(
        ExcelArchive $excelArchive,
    ) {
        $this->excelArchive = $excelArchive;

        $this->excelContents = new ExcelContents(
            $this->excelArchive
        );

        $this->sharedStrings = $this->excelContents
            ->getSharedStrings();
    }

    /**
    *   load
    *
    *   @param string $sheet_name
    *   @return ExcelSheet
    */
    public function load(
        string $sheet_name,
    ): ExcelSheet {
        $sheetParts = $this->excelContents
            ->getSheetParts($sheet_name);

        $excelNodes = $sheetParts->loadData();

        $data = [];

        foreach ($excelNodes as $row) {
            if ($row->name === 'row') {
                $data = array_replace(
                    $data,
                    $this->rowNodeToData($row),
                );
            }
        }

        $excelSheet = new ExcelSheet(
            $sheet_name,
        );

        return $excelSheet->setMappingData($data);
    }

    /**
    *   rowNodeToData
    *
    *   @param ExcelNode $row
    *   @return array<int,array<int, int|float|string|DateTimeImmutable>>
    */
    private function rowNodeToData(
        ExcelNode $row,
    ): array {
        $data = [];

        foreach ($row->children as $cell) {
            if ($cell->name !== 'c') {
                continue;
            }

            if (!isset($row->attribute['r'])) {
                throw new RuntimeException(
                    "cell attribute not has address" .
                    print_r($cell, true),
                );
            }

            $address = $cell->attribute['r'];

            $location = ExcelAddress::addressToLocation(
                $address,
            );

            $value = $this->cellNodeToData($cell);

            $data[$location[0]][$location[1]] = $value;
        }

        return $data;
    }

    /**
    *   cellNodeToData
    *
    *   @param ExcelNode $cell
    *   @return int|float|string|DateTimeImmutable
    */
    private function cellNodeToData(
        ExcelNode $cell,
    ): int|float|string|DateTimeImmutable {
        $type = $cell->attribute['t'] ?? 'n';

        $value = $this->extractValueNode($cell);

        if ($value === null) {
            throw new RuntimeException(
                "v node must be numeric. null given."
            );
        }

        $data = match ($type) {
            'n' => $this->strToNumber($value),
            'd' => new DateTimeImmutable($value),
            's' => $this->findBySharedString($value),
            default => throw new RuntimeException(
                "data type is not supported:" .
                print_r($cell, true),
            ),
        };

        return $data;
    }

    /**
    *   extractValueNode
    *
    *   @param ExcelNode $cell
    *   @return ?string
    */
    private function extractValueNode(
        ExcelNode $cell,
    ): ?string {
        $value = '';

        foreach ($cell->children as $v) {
            if ($v->name === 'v') {
                $value = $v->text;
                break;
            }
        }

        return $value;
    }

    /**
    *   strToNumber
    *
    *   @param string $value
    *   @return int|float
    */
    private function strToNumber(
        string $value,
    ): int|float {
        return strpos(strval($value), '.') === false ?
        intval($value) :
        floatval($value);
    }

    /**
    *   findBySharedString
    *
    *   @param string $no
    *   @return string
    */
    private function findBySharedString(
        string $no,
    ): string {
        $value = $this->sharedStrings
            ->findBySharedString(
                (int)$no,
            );

        if ($value === null) {
            throw new RuntimeException(
                "shared string not found.no:{$no}"
            );
        }

        return $value;
    }
}
