<?php

/**
*   ExcelArchive
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel;

use DOMDocument;
use RuntimeException;
use ZipArchive;
use Concerto\excel\exception\ExcelArchveLoadException;
use Concerto\excel\parts\ExcelContents;

class ExcelArchive
{
    /**
    *   @var string
    */
    protected string $excel_path;

    /**
    *   @var ZipArchive
    */
    protected ZipArchive $zip;

    /**
    *   __construct
    *
    *   @param string $excel_path
    */
    public function __construct(
        string $excel_path,
    ) {
        $this->excel_path = $excel_path;

        $this->unzip();
    }

    /**
    *   unzip
    *
    *   @return void
    */
    private function unzip(): void
    {
        $this->zip = $this->zip ?? new ZipArchive();

        $open_result = $this->zip->open(
            $this->excel_path,
        );

        if ($open_result !== true) {
            throw new RuntimeException(
                implode(
                    PHP_EOL,
                    [
                        "file could not be opened:{$this->excel_path}",
                        "error code:{$open_result}"
                    ],
                ),
            );
        }
    }

    /**
    *   close
    *
    *   @return void
    */
    public function close(): void
    {
        if ($this->zip->close() === false) {
            throw new RuntimeException(
                "zip archive file could not be closed",
            );
        }
    }

    /**
    *   load
    *
    *   @param string $file_path
    *   @return DOMDocument
    */
    public function load(
        string $file_path,
    ): DOMDocument {
        $dom = new DOMDocument();

        $loaded = $dom->loadXML(
            $this->loadString($file_path),
        );

        if ($loaded === false) {
            throw new ExcelArchveLoadException(
                "xml load error:{$file_path}",
            );
        }

        return $dom;
    }

    /**
    *   loadString
    *
    *   @param string $file_path
    *   @return string
    */
    public function loadString(
        string $file_path,
    ): string {
        $xml = $this->zip->getFromName(
            $file_path,
        );

        if ($xml === false) {
            throw new ExcelArchveLoadException(
                "file not found:{$file_path}",
            );
        }

        return $xml;
    }

    /**
    *   save
    *
    *   @param string $file_path
    *   @param DOMDocument $domDocument
    *   @return void
    */
    public function save(
        string $file_path,
        DOMDocument $domDocument,
    ): void {
        $xml = $domDocument->saveXML();

        if ($xml === false) {
            throw new RuntimeException(
                "xml to string error:{$file_path}",
            );
        }

        $this->saveString($file_path, $xml);

        $this->unzip();
    }

    /**
    *   saveString
    *
    *   @param string $file_path
    *   @param string $xml
    *   @return void
    */
    public function saveString(
        string $file_path,
        string $xml,
    ): void {
        $result = $this->zip->addFromString(
            $file_path,
            $xml,
            ZipArchive::FL_OVERWRITE |
                ZipArchive::FL_ENC_UTF_8,
        );

        if ($result === false) {
            throw new RuntimeException(
                "xml save error:{$file_path}",
            );
        }
    }

    /**
    *   filepath
    *
    *   @return string
    */
    public function filepath(): string
    {
        return $this->excel_path;
    }

    /**
    *   addFile
    *
    *   @param string $file_path
    *   @param string $zip_path
    *   @param int $flags see ZipArchive::addFile->$flags
    *   @return void
    */
    public function addFile(
        string $file_path,
        string $zip_path,
        int $flags = ZipArchive::FL_OVERWRITE |
        ZipArchive::FL_ENC_UTF_8,
    ): void {
        $result = $this->zip->addFile(
            filepath:$file_path,
            entryname:$zip_path,
            flags:$flags,
        );

        if ($result === false) {
            throw new RuntimeException(
                "xml save error:{$file_path}",
            );
        }
    }
}
