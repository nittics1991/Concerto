<?php

/**
*   ExcelTemplateWriter
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel;

use RuntimeException;
use Concerto\excel\ExcelBook;
use Concerto\excel\ExcelDownloader;

class ExcelTemplateWriter
{
    /**
    *   @var string
    */
    private string $temp_dir;

    /**
    *   @var ExcelBook
    */
    private ExcelBook $excelBook;

    /**
    *   @var ExcelSheet
    */
    private ExcelSheet $excelSheet;

    /**
    *   @var string
    */
    private string $created_file_path;

    /**
    *   __construct
    */
    public function __construct()
    {
        $this->createWorkDir();
    }

    /**
    *   createWorkDir
    *
    *   @return void
    */
    private function createWorkDir(): void
    {
        $temp_dir = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            uniqid();

        if (!mkdir($temp_dir)) {
            throw new RuntimeException(
                "temp dir create error",
            );
        }

        $this->temp_dir = $temp_dir;
    }

    /**
    *   getExcelBook
    *
    *   @return ExcelBook
    */
    public function getExcelBook(): ExcelBook
    {
        return $this->excelBook;
    }

    /**
    *   getExcelSheet
    *
    *   @return ExcelSheet
    */
    public function getExcelSheet(): ExcelSheet
    {
        return $this->excelSheet;
    }

    /**
    *   open
    *
    *   @param string $excel_path
    *   @return static
    */
    public function open(
        string $excel_path,
    ): static {
        if (!file_exists($excel_path)) {
            throw new RuntimeException(
                "excel file not found:{$excel_path}",
            );
        }

        $this->excelBook = new ExcelBook(
            $this->temp_dir,
            $excel_path,
        );

        return $this;
    }

    /**
    *   save
    *
    *   @param string $file_path
    *   @return static
    */
    public function save(
        string $file_path,
    ): static {
        $this->created_file_path = $this->created_file_path ??
            $this->getExcelBook()->close();

        if (!copy($this->created_file_path, $file_path)) {
            throw new RuntimeException(
                "work excel file create error",
            );
        }

        return $this;
    }

    /**
    *   download
    *
    *   @param string $file_name
    *   @return static
    */
    public function download(
        string $file_name,
    ): static {
        $this->created_file_path = $this->created_file_path ??
            $this->getExcelBook()->close();

        ExcelDownloader::send(
            $this->created_file_path,
            $file_name,
        );

        return $this;
    }

    /**
    *   sheets
    *
    *   @param string $sheet_name
    *   @return static
    */
    public function sheet(
        string $sheet_name,
    ): static {
        $this->excelSheet =
            $this->getExcelBook()->sheet($sheet_name);

        return $this;
    }

    /**
    *   loadSheet
    *
    *   @param string $sheet_name
    *   @return static
    */
    public function loadSheet(
        string $sheet_name,
    ): static {
        $this->excelSheet =
            $this->getExcelBook()->loadSheet($sheet_name);

        return $this;
    }

    /**
    *   addData
    *
    *   @param string $cell_address
    *   @param array<mixed[]> $data
    *   @param bool $toIndexed
    *   @return static
    */
    public function addData(
        string $cell_address,
        array $data,
        bool $toIndexed = false,
    ): static {
        $this->getExcelSheet()->addData(
            $cell_address,
            $data,
            $toIndexed,
        );

        return $this;
    }

    /**
    *   expandData
    *
    *   @return static
    */
    public function expandData(): static
    {
        $this->getExcelSheet()->expandData();

        return $this;
    }

    /**
    *   toArray
    *
    *   @return array<array<int|float|string|\DateTimeInterface>>
    */
    public function toArray(): array
    {
        return $this->getExcelSheet()->toArray();
    }
}
