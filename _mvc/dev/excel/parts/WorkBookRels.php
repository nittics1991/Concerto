<?php

/**
*   WorkBookRels
*
*   @version 240724
*/

declare(strict_types=1);

namespace dev\excel\parts;

use DOMElement;
use DOMXPath;
use RuntimeException;
use dev\excel\parts\OfficeParts;

class WorkBookRels extends OfficeParts
{
    /**
    *   {inheritDoc}
    */
    protected string $file_path =
        'xl/_rels/workbook.xml.rels';

    /**
    *   {inheritDoc}
    */
    protected string $namespace =
        'http://schemas.openxmlformats.org/package/2006/relationships';

    /**
    *   findSheetFileName
    *
    *   @param string $sheet_id
    *   @return string
    */
    public function findSheetFileName(
        string $sheet_id,
    ): string {
        $node_list = $this->xpath->query(
            "//m:Relationship[@Id='{$sheet_id}']"
        );

        if (
            $node_list === false ||
            count($node_list) !== 1
        ) {
            throw new RuntimeException(
                "sheet name search error. id:{$sheet_id}",
            );
        }

        $element = $node_list->item(0);

        if (!$element instanceof DOMElement) {
            throw new RuntimeException(
                "dom element not found. id:{$sheet_id}",
            );
        }

        $attribute = $element->getAttribute('Target');

        if ($attribute === '') {
            throw new RuntimeException(
                "target not found:{$sheet_id}",
            );
        }

        return $attribute;
    }
}
