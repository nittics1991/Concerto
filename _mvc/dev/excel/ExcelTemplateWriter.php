<?php

/**
*   ExcelTemplateWriter
*
*   @version 240724
*/

declare(strict_types=1);

namespace dev\excel;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use dev\excel\ExcelBook;
use dev\excel\ExcelDownloader;

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
    *   @var bool
    */
    private bool $debug_mode = false;

    /**
    *   __construct
    */
    public function __construct()
    {
        $this->createWorkDir();
    }

    /**
    *   __destruct
    */
    public function __destruct()
    {
        if (!$this->debug_mode) {
            $this->cleanTempDir();
        }
    }

    /**
    *   createWorkDir
    *
    *   @return void
    */
    private function createWorkDir(): void
    {
        $temp_dir = (string)tempnam(
            sys_get_temp_dir(),
            'ET_'
        ) . DIRECTORY_SEPARATOR .
            uniqid();

        if (!mkdir($temp_dir)) {
            throw new RuntimeException(
                "temp dir create error",
            );
        }

        $this->temp_dir = $temp_dir;
    }

    /**
    *   open
    *
    *   @param string $excel_path
    *   @return ExcelBook
    */
    public function open(
        string $excel_path,
    ): ExcelBook {
        if (!file_exists($excel_path)) {
            throw new RuntimeException(
                "excel file not found:{$excel_path}",
            );
        }

        $this->excelBook = new ExcelBook(
            $this->temp_dir,
            $excel_path,
        );

        return $this->excelBook;
    }

    /**
    *   save
    *
    *   @param string $file_path
    *   @return void
    */
    public function save(
        string $file_path,
    ): void {
        $excel_path = $this->excelBook->save();

        if (!copy($excel_path, $file_path)) {
            throw new RuntimeException(
                "work excel file create error",
            );
        }
    }

    /**
    *   download
    *
    *   @param string $file_name
    *   @return void
    */
    public function download(
        string $file_name,
    ): void {
        $excel_path = $this->excelBook->save();

        ExcelDownloader::send(
            $excel_path,
            $file_name,
        );
    }

    /**
    *   cleanTempDir
    *
    *   @return static
    */
    public function cleanTempDir(): static
    {
        if (!file_exists($this->temp_dir)) {
            return $this;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->temp_dir,
                FilesystemIterator::KEY_AS_PATHNAME |
                    FilesystemIterator::CURRENT_AS_FILEINFO |
                    FilesystemIterator::SKIP_DOTS |
                    FilesystemIterator::UNIX_PATHS,
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                rmdir($fileInfo->getPathname());
            } else {
                unlink($fileInfo->getPathname());
            }
        }

        rmdir($this->temp_dir);

        return $this;
    }

    /**
    *   debugMode
    *
    *   @return static
    */
    public function debugMode(): static
    {
        $this->debug_mode = true;
        return $this;
    }
}
