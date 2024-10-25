<?php

/**
*   SheetParts
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use DOMElement;
use RuntimeException;
use Concerto\excel\ExcelArchive;
use Concerto\excel\parts\{
    ExcelNode,
    OfficeParts,
};

class SheetParts extends OfficeParts
{
    /**
    *   {inheritDoc}
    */
    protected string $file_path;

    /**
    *   {inheritDoc}
    */
    protected string $namespace =
        'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    /**
    *   __construct
    *
    *   @param ExcelArchive $archive
    *   @param string $file_path
    */
    public function __construct(
        ExcelArchive $archive,
        string $file_path,
    ) {
        $this->file_path = $file_path;

        parent::__construct($archive);
    }

    /**
    *   addSheetData
    *
    *   @param ExcelNode[] $excelNodes
    *   @return void
    */
    public function addSheetData(
        array $excelNodes,
    ): void {
        $dom_string = $this->excelNodeToXml($excelNodes);

        $target = $this->queryXml('//m:sheetData');

        if (strlen($dom_string) > 0) {
            $fragment = $this->domstringToFragment(
                $dom_string
            );

            $target->appendChild($fragment);
        }

        $domDocument = $target->ownerDocument;

        if ($domDocument === null) {
            throw new RuntimeException(
                "faild to get DOMDocument from DOMElement",
            );
        }

        $this->archive->save(
            $this->file_path,
            $domDocument,
        );
    }

    /**
    *   loadData
    *
    *   @return ExcelNode[]
    */
    public function loadData(): array
    {
        $node_list = $this->xpath->query(
            '//m:sheetData/*',
        );

        if (
            $node_list === false ||
            count($node_list) === 0
        ) {
            return [];
        }

        return $this->xmlToExcelNodes(
            $node_list,
        );
    }
}
