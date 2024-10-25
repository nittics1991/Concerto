<?php

/**
*   WorkBookParts
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use DOMElement;
use DOMXPath;
use RuntimeException;
use Concerto\excel\parts\OfficeParts;

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
        $element = $this->queryXml(
            "//m:sheet[@name='{$sheet_name}']",
        );

        $attribute = $element->getAttribute('r:id');

        if ($attribute === '') {
            throw new RuntimeException(
                "relation id not found:{$sheet_name}",
            );
        }

        return $attribute;
    }
}
