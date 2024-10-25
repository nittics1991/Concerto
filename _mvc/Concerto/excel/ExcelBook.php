<?php

/**
*   ExcelBook
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel;

use RuntimeException;
use Concerto\excel\{
    ExcelArchive,
    ExcelSheet,
};
use Concerto\excel\writer\CellDataWriter;
use Concerto\excel\reader\CellDataReader;

class ExcelBook
{
    /**
    *   @var string
    */
    private string $temp_dir;

    /**
    *   @var string
    */
    private string $template_path;

    /**
    *   @var string
    */
    private string $book_path;

    /**
    *   @var ExcelSheet[]
    */
    private array $sheets = [];

    /**
    *   @var string[]
    */
    private array $sheet_names = [];

    /**
    *   @var ExcelArchive
    */
    private ExcelArchive $excelArchive;

    /**
    *   __construct
    *
    *   @param string $temp_dir
    *   @param string $template_path
    */
    public function __construct(
        string $temp_dir,
        string $template_path,
    ) {
        $this->temp_dir = $temp_dir;

        $this->template_path = $template_path;

        $this->copyTemplate();
    }

    /**
    *   copyTemplate
    *
    *   @return void
    */
    private function copyTemplate(): void
    {
        $copy_book_path = $this->temp_dir .
            DIRECTORY_SEPARATOR .
            basename($this->template_path);

        if (!copy($this->template_path, $copy_book_path)) {
            throw new RuntimeException(
                "work excel file create error",
            );
        }

        $this->book_path = $copy_book_path;
    }

    /**
    *   getSheetNames
    *
    *   @return string[]
    */
    public function getSheetNames(): array
    {
        return $this->sheet_names;
    }

    /**
    *   sheet
    *
    *   @param string $sheet_name
    *   @return ExcelSheet
    */
    public function sheet(
        string $sheet_name,
    ): ExcelSheet {
        $pos = array_search(
            $sheet_name,
            $this->sheet_names,
            true,
        );

        if ($pos !== false) {
            return $this->sheets[$pos];
        }

        $excelSheet = new ExcelSheet(
            $sheet_name,
        );

        $keys = array_keys($this->sheet_names);

        $pos = $keys === [] ?
            0 : max($keys) + 1;

        $this->sheet_names[$pos] = $sheet_name;

        $this->sheets[$pos] = $excelSheet;

        return $this->sheets[$pos];
    }

    /**
    *   close
    *
    *   @return string file path
    */
    public function close(): string
    {
        if (!isset($this->excelArchive)) {
            $this->excelArchive = new ExcelArchive(
                $this->book_path,
            );
        }

        $cellDataWriter = new CellDataWriter(
            $this->excelArchive,
        );

        $cellDataWriter->save($this);

        return $this->book_path;
    }

    /**
    *   loadSheet
    *
    *   @param string $sheet_name
    *   @return ExcelSheet
    */
    public function loadSheet(
        string $sheet_name,
    ): ExcelSheet {
        if (!isset($this->excelArchive)) {
            $this->excelArchive = new ExcelArchive(
                $this->book_path,
            );
        }

        $cellDataReader = new CellDataReader(
            $this->excelArchive,
        );

        return $cellDataReader->load($sheet_name);
    }
}
