<?php

namespace Concerto\excel;

use DOMDocument;
use RuntimeException;
use ZipArchive;
use Concerto\excel\ExcelSheet

class ExcelBook
{
    private const TEMP_NAME_PREFIX = '';

    private string $targetFilePath;

    private ZipArchive $xlsx;

    private DOMDocument $workbook;

    /**
    *   @var string[] [sheetId => sheetName,...]
    */
    private array $sheets = [];

    /**
    *   @var ExcelSheet[] [sheetName => ExcelSheet,...]
    */
    private array $excelSheets = [];

    public function __construct(
        private string $srcFileName,
    ) {
        $this->open();
    }

    private function open():void
    {
        $this->targetFilePath = tempnam(
            sys_get_temp_dir(),
            static::TEMP_NAME_PREFIX
        );

        if ($this->targetFilePath === false) {
            throw new RuntimeException(
                "temp file name create error",
            );
        }

        $isCopied = copy(
            $this->srcFileName,
            $this->targetFilePath,
        );
        
        if ($isCopied === false) {
            throw new RuntimeException(
                "xlsx file create error",
            );
        }

        $this->xlsx = new ZipArchive();

        $isOpend = $this->xlsx->open($targetFilePath);
        
        if ($isOpend === false) {
            throw new RuntimeException(
                "xlsx file open error",
            );
        }
    }

    public function hasSheetByNameInArchive(
        string $sheetName,
    ) :bool {
        if (empty($this->sheets)) {
            $this->analyzeSheetConfigration();
        }

        return in_array($sheetName, $this->sheets);
    }

    public function getSheetByNameInArchive(
        string $sheetName,
    ):?DOMDocument
    {
        if ($this->hasSheetByName($sheetName)) {
            return　$this->getSheet(
                (int)array_keys(
                    $this->sheets,
                    $sheetName,
                    true,
                )
            );
        }

        return null;
    }

    private function analyzeSheetConfigration():void
    {
        $sheetNodeList = $this->getWorkbook()
            ->getElementsByTagName('sheet');

        foreach($sheetNodeList as $index => $element) {
            $name = $element->getAttribute('name');

            if (empty($name)) {
                throw new RuntimeException(
                    "sheet 'name' attribute not defined:{$index}",
                );
            }
            
            $this->sheets[$index] = $name;
        }
    }

    private function getWorkbook():DOMDocument
    {
        if (isset($this->workbook)) {
            return $this->workbook;
        }

        $domString = $this->xlsx->getFromName(
            'xl/workbook.xml',
        );

        if ($domString === false) {
            throw new RuntimeException(
                "xl/workbook.xml read error",
            );
        }

        $this->workbook = $this->createDocumentFromDomString(
            $domString,
        );

        return $this->workbook;
    }

    private function getSheet(
        int $sheetId,
    ):DOMDocument
    {
        $domString = $this->xlsx->getFromName(
            "xl/worksheets/sheet{$sheetId}.xml",
        );

        if ($domString === false) {
            throw new RuntimeException(
                "xl/worksheets/sheet{$sheetId}.xml read error",
            );
        }

        return $this->createDocumentByDomString(
            $domString,
        );
    }

    private function createDocumentByDomString(
        string $domString,
    ): DOMDocument {
        $domDocument = new DOMDocument();
        
        $document = $domDocument->loadXML(
            $domString,
        );

        if ($document === false) {
            throw new RuntimeException(
                "create DOMDocument error:" .
                PHP_EOL .
                $domString
            );
        }

        return $document;
    }

    public function hasExcelSheetByName(
        string $sheetName,
    ):bool {
        return in_array($sheetName, $this->excelSheets);;
    }

    public function addExcelSheet(
        string $sheetName,
        ExcelSheet $excelSheet,
        bool $allowOverride = true,
    ):static {
        if (
            $allowOverride ||
            !$this->hasExcelSheetByName($sheetName)
        ) {
            $this->excelSheets[$sheetName] = $excelSheet;
        }

        return static;
    }

    public function saveAs()
        string $filePath,
    ) {
        $this->save();

        $isCopied = copy(
            $this->targetFilePath,
            $filePath,
        );
        
        if ($isCopied === false) {
            throw new RuntimeException(
                "xlsx copy error",
            );
        }
        
        return static;
    }

    public function save():static
    {









        return static;
    }












            
    public function filePath()
    {
        $this->targetFilePath;
    }
}
