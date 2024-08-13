<?php

/**
*   ExcelArchive
*
*   @version 240724
*/

declare(strict_types=1);

namespace dev\excel;

use DOMDocument;
use RuntimeException;
use ZipArchive;
use dev\excel\parts\ExcelContents;

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
        $this->zip = new ZipArchive();

        $open_result = $this->zip->open(
            $this->excel_path,
        );

        if ($open_result !== true) {
            throw new RuntimeException(
                implode(
                    PHP_EOL,
                    [
                        "file could not be opened:{$this->excel_path}",
                        "error code:{open_result}"
                    ],
                ),
            );
        }
    }
    
    /**
    *   getContents
    *
    *   @return ExcelContents
    */
    public function getContents(): ExcelContents
    {
        return new ExcelContents($this);
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
        $xml = $this->zip->getFromName(
            $file_path,
        );

        if ($xml === false) {
            throw new RuntimeException(
                "file not found:{$file_path}",
            );
        }

        $dom = new DOMDocument();

        $loaded = $dom->loadXML($xml);

        if ($loaded === false) {
            throw new RuntimeException(
                "sheetX.xml load error:{$file_path}",
            );
        }

        return $dom;
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
}
