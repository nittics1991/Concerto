<?php

/**
*   WorkBookParts
*
*   @version 240724
*/

declare(strict_types=1);

namespace dev\excel\parts;

use DOMElement;
use DOMXPath;
use RuntimeException;
use dev\excel\parts\OfficeParts;

class WorkBookParts extends OfficeParts
{
    /**
    *   {inheritDoc}
    */
    protected string $file_path =
        'xl/workbook.xml';

    /**
    *   {inheritDoc}
    */
    protected string $namespace =
        'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    /**
    *   findSheetPartsId
    *
    *   @param string $sheet_name
    *   @return string
    */
    public function findSheetPartsId(
        string $sheet_name,
    ): string {
        $node_list = $this->xpath->query(
            "//m:sheet[@name='{$sheet_name}']"
        );

        if (
            $node_list === false ||
            count($node_list) !== 1
        ) {
            throw new RuntimeException(
                "sheet name search error:{$sheet_name}",
            );
        }

        $element = $node_list->item(0);

        if (!$element instanceof DOMElement) {
            throw new RuntimeException(
                "dom element not found:{$sheet_name}",
            );
        }

        $attribute = $element->getAttribute('r:id');

        if ($attribute === '') {
            throw new RuntimeException(
                "relation id not found:{$sheet_name}",
            );
        }

        return $attribute;
    }
}
