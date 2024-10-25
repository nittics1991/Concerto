<?php

/**
*   ExcelSheetDirectWriter
*
*   使用方法
*       テンプレートシートに文字列のデータタイトル行を準備する事
*       複数回addData()する場合、データは1列空く
*
*   @version 241023
*/

declare(strict_types=1);

namespace Concerto\excel;

use ArrayIterator;
use IteratorIterator;
use MultipleIterator;
use RuntimeException;
use Concerto\excel\{
    ExcelAddress,
    ExcelArchive,
};
use Concerto\excel\parts\ExcelContents;

class ExcelSheetDirectWriter
{
    /**
    *   @var int
    */
    private const MAX_XML_LENGTH = 256 * 1024;

    /**
    *   @var string
    */
    private string $template_path;

    /**
    *   @var string
    */
    private string $sheet_name;

    /**
    *   @var string
    */
    private string $base_address;

    /**
    *   @var MultipleIterator
    */
    private MultipleIterator $dataset;

    /**
    *   @var string
    */
    private string $temp_dir;

    /**
    *   @var string
    */
    private string $work_file;

    /**
    *   @var ExcelArchive
    */
    private ExcelArchive $excelArchive;

    /**
    *   @var string
    */
    private string $sheet_work_file;

    /**
    *   @var resource
    */
    private $sheet_handle;

    /**
    *   @var string
    */
    private string $sheet_to_last;

    /**
    *   @var string
    */
    private string $shared_string_work_file;

    /**
    *   @var resource
    */
    private $shared_string_handle;

    /**
    *   @var string
    */
    private string $shared_string_to_last;

    /**
    *   @var int
    */
    private int $shared_string_index;

    /**
    *   __construct
    *
    *   @param string $template_path
    *   @param string $sheet_name
    *   @param string $base_address
    */
    public function __construct(
        string $template_path,
        string $sheet_name,
        string $base_address = 'A2',
    ) {
        $this->template_path = $template_path;

        $this->sheet_name = $sheet_name;

        $this->base_address = $base_address;

        $this->dataset = new MultipleIterator(
            MultipleIterator::MIT_NEED_ANY |
                MultipleIterator::MIT_KEYS_NUMERIC,
        );
    }

    /**
    *   addData
    *
    *   @param iterable<array{?scalar[]}> $data
    *   @return static
    */
    public function addData(
        iterable $data,
    ): static {
        $this->dataset->attachIterator(
            is_array($data) ?
                new ArrayIterator($data) :
                new IteratorIterator($data),
        );

        return $this;
    }

    /**
    *   save
    *
    *   @return string saved file path
    */
    public function save(): string
    {
        $this->createWorkfile(
            $this->template_path,
        );

        $this->excelArchive = new ExcelArchive(
            $this->work_file,
        );

        [$sheet_file, $sheet_split_pattern] =
            $this->prepareForSheet();

        [$shared_string_file, $shared_string_split_pattern] =
            $this->prepareForSharedString();

        [$row_no, $base_column_no] =
            ExcelAddress::addressToLocation(
                $this->base_address,
            );

        $sheet_xml = $sheet_split_pattern;
        $shared_string_xml = '';
        $is_first_row = true;
        $column_widths = [];

        foreach ($this->dataset as $row) {
            if (
                strlen($shared_string_xml) > self::MAX_XML_LENGTH ||
                strlen($sheet_xml) > self::MAX_XML_LENGTH
            ) {
                $this->writeFile(
                    $this->sheet_handle,
                    $sheet_xml,
                );

                $this->writeFile(
                    $this->shared_string_handle,
                    $shared_string_xml,
                );

                $sheet_xml = '';
                $shared_string_xml = '';
            }

            $column_no = $base_column_no;

            $sheet_xml .= $this->createStartRow($row_no);

            foreach ($row as $table_no => $columns) {
                $columns = (array)$columns;

                if ($is_first_row) {
                    $column_widths[$table_no] =
                        count($columns);
                }

                if ($columns === []) {
                    $column_no += $column_widths[$table_no];
                }

                foreach ($columns as $column) {
                    [$sheet_tmp_xml, $shared_string_tmp_xml] =
                        $this->createColumn(
                            $row_no,
                            $column_no,
                            $column,
                        );

                    $sheet_xml .= $sheet_tmp_xml;
                    $shared_string_xml .= $shared_string_tmp_xml;

                    $column_no++;
                }

                //テーブルが変わるとき1列開ける
                $column_no++;
            }

            $sheet_xml .= $this->createEndRow();

            $row_no++;

            if ($is_first_row) {
                $is_first_row = false;
            }
        }

        if (strlen($sheet_xml) > 0) {
            $this->writeFile(
                $this->sheet_handle,
                $sheet_xml,
            );

            $this->writeFile(
                $this->shared_string_handle,
                $shared_string_xml,
            );
        }

        $this->writeFile(
            $this->sheet_handle,
            $this->sheet_to_last
        );

        $this->writeFile(
            $this->shared_string_handle,
            $this->shared_string_to_last
        );

        $this->closeFile($this->sheet_handle);

        $this->closeFile($this->shared_string_handle);

        $this->writeZipFile(
            $this->sheet_work_file,
            $sheet_file,
        );

        $this->writeZipFile(
            $this->shared_string_work_file,
            $shared_string_file,
        );

        $this->excelArchive->close();

        return $this->work_file;
    }

    /**
    *   createWorkfile
    *
    *   @param string $template_path
    *   @return void
    */
    private function createWorkfile(
        string $template_path,
    ): void {
        $this->temp_dir = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            uniqid();

        if (!mkdir($this->temp_dir)) {
            throw new RuntimeException(
                "temp dir create error" .
                    PHP_EOL .
                    print_r($this, true),
            );
        }

        $this->work_file = implode(
            DIRECTORY_SEPARATOR,
            [
                $this->temp_dir,
                basename($template_path),
            ],
        );

        $copied = copy($template_path, $this->work_file);

        if ($copied === false) {
            throw new RuntimeException(
                "temp dir create error" .
                    PHP_EOL .
                    print_r($this, true),
            );
        }

        $this->sheet_work_file = $this->temp_dir .
            DIRECTORY_SEPARATOR .
            uniqid() . '.xml';


        $this->shared_string_work_file = $this->temp_dir .
            DIRECTORY_SEPARATOR .
            uniqid() . '.xml';
    }

    /**
    *   prepareForSheet
    *
    *   @return array{string,string}
    */
    private function prepareForSheet(): array
    {
        $sheet_file = $this->findBySheetFileName(
            $this->sheet_name,
        );

        //空シートの場合<sheetData/>でエラーになる
        $sheet_split_pattern = '</row>';

        [$this->sheet_handle, $this->sheet_to_last] =
            $this->prepareFor(
                $sheet_file,
                $sheet_split_pattern,
                $this->sheet_work_file,
            );

        return [
            $sheet_file,
            $sheet_split_pattern,
        ];
    }

    /**
    *   prepareForSharedString
    *
    *   @return array{string,string}
    */
    private function prepareForSharedString(): array
    {
        //ブックに文字列データが無いと存在しない
        $shared_string_file = 'xl/sharedStrings.xml';

        $shared_string_split_pattern = '</sst>';

        [$this->shared_string_handle, $tmp_xml] =
            $this->prepareFor(
                $shared_string_file,
                $shared_string_split_pattern,
                $this->shared_string_work_file,
            );

        $this->shared_string_to_last =
            "{$shared_string_split_pattern}{$tmp_xml}";

        $contents = $this->excelArchive->loadString(
            $shared_string_file,
        );

        $this->shared_string_index =
            mb_substr_count($contents, '<si>');

        return [
            $shared_string_file,
            $shared_string_split_pattern,
        ];
    }

    /**
    *   findBySheetFileName
    *
    *   @param string $sheet_name
    *   @return string
    */
    private function findBySheetFileName(
        string $sheet_name,
    ): string {
        $excelContents = new ExcelContents(
            $this->excelArchive,
        );

        $sheet_id = $excelContents
            ->findSheetPartsId($sheet_name);

        return $excelContents
            ->findSheetFileName($sheet_id);
    }

    /**
    *   prepareFor
    *
    *   @param string $parts_file
    *   @param string $pattern
    *   @param string $work_file
    *   @return array{resource, string}
    */
    private function prepareFor(
        string $parts_file,
        string $pattern,
        string $work_file,
    ): array {
        $contents = $this->excelArchive->loadString(
            $parts_file,
        );

        [$from_top, $to_last] =
            $this->splitContents(
                $pattern,
                $contents,
            );

        $handle = $this->openFile(
            $work_file,
            'w+',
        );

        $this->writeFile(
            $handle,
            $from_top,
        );

        return [$handle, $to_last];
    }

    /**
    *   openFile
    *
    *   @param string $zip_path
    *   @return resource
    */
    private function openFile(
        string $zip_path,
        string $mode,
    ) {
        $handle = fopen($zip_path, $mode);

        if ($handle === false) {
            throw new RuntimeException(
                "file read error:{$zip_path}" .
                    PHP_EOL .
                    print_r($this, true),
            );
        }

        return $handle;
    }

    /**
    *   closeFile
    *
    *   @param resource $handle
    *   @return void
    */
    private function closeFile(
        $handle,
    ): void {
        $closed = fclose($handle);

        if ($closed === false) {
            throw new RuntimeException(
                "file close error:{$handle}" .
                    PHP_EOL .
                    print_r($this, true),
            );
        }
    }

    /**
    *   writeFile
    *
    *   @param resource $handle
    *   @param string $data
    *   @return void
    */
    private function writeFile(
        $handle,
        string $data,
    ): void {
        if ($data === '') {
            return;
        }

        $writed = fwrite($handle, $data);

        if ($writed === false) {
            throw new RuntimeException(
                "file write error:{$handle}" .
                    PHP_EOL .
                    print_r($this, true),
            );
        }
    }

    /**
    *   splitContents
    *
    *   @param string $pattern
    *   @param string $contents
    *   @return array{string, string}
    */
    private function splitContents(
        string $pattern,
        string $contents,
    ): array {
        $splited = mb_split(
            $pattern,
            $contents,
        );

        if (
            $splited === false ||
                count($splited) < 2
        ) {
            throw new RuntimeException(
                "target element search error:{$pattern}" .
                    PHP_EOL .
                    print_r($this, true),
            );
        }

        $front = implode(
            $pattern,
            array_slice(
                $splited,
                0,
                count($splited) - 1,
            )
        );

        $rear = $splited[count($splited) - 1];

        return [$front, $rear];
    }

    /**
    *   createStartRow
    *
    *   @param int $row_no
    *   @return string
    */
    private function createStartRow(
        int $row_no,
    ): string {
        return '<row r="' . $row_no . '">';
    }

    /**
    *   createEndRow
    *
    *   @return string
    */
    private function createEndRow(): string
    {
        return '</row>';
    }

    /**
    *   createColumn
    *
    *   @param int $row_no
    *   @param int $column_no
    *   @param int|float|string|bool|null $column
    *   @return array{string,string}
    */
    private function createColumn(
        int $row_no,
        int $column_no,
        int|float|string|bool|null $column,
    ): array {
        $shared_string_xml = '';

        $sheet_xml = '<c r="' .
            ExcelAddress::locationToAddress(
                [$row_no, $column_no,],
            ) .
            '" t="';

        $sheet_xml .= match (true) {
            is_int($column), is_float($column) => 'n',
            default => 's',
        };

        $sheet_xml .= '"><v>';

        if (is_int($column) || is_float($column)) {
            $sheet_xml .= $column;
        } else {
            [$sheet_xml_string, $shared_string_xml] =
                $this->createStrings((string)$column);

            $sheet_xml .= $sheet_xml_string;
        }

        $sheet_xml .= '</v></c>';

        return [$sheet_xml, $shared_string_xml];
    }

    /**
    *   createSharedStrings
    *
    *   @param string $column
    *   @return array{string,string}
    */
    private function createStrings(
        string $column,
    ): array {
        $sheet_xml = (string)$this->shared_string_index;

        $shared_string_xml =
            $this->createSharedStrings($column);

        $this->shared_string_index++;

        return [
            $sheet_xml,
            $shared_string_xml,
        ];
    }

    /**
    *   createSharedStrings
    *
    *   @param string $string
    *   @return string
    */
    private function createSharedStrings(
        string $string,
    ): string {
        return '<si><t xml:space="preserve">' .
            htmlentities(
                $string,
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1,
            ) .
            '</t></si>';
    }

    /**
    *   writeZipFile
    *
    *   @param string $wrok_file
    *   @param string $zip_file
    *   @return void
    */
    private function writeZipFile(
        string $wrok_file,
        string $zip_file,
    ): void {
        $this->excelArchive->addFile(
            $wrok_file,
            $zip_file,
        );
    }
}
