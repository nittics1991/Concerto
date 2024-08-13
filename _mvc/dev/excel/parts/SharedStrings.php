<?php

/**
*   SharedStrings
*
*   @version 240730
*/

declare(strict_types=1);

namespace dev\excel\parts;

use DOMElement;
use DOMXPath;
use RuntimeException;
use dev\excel\parts\OfficeParts;

class SharedStrings extends OfficeParts
{
    /**
    *   {inheritDoc}
    */
    protected string $file_path =
        'xl/sharedStrings.xml';

    /**
    *   {inheritDoc}
    */
    protected string $namespace =
        'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    /**
    *   getAllString
    *
    *   @return string[]
    */
    public function getAllString(): array
    {
        $node_list = $this->xpath->query(
            "//m:si/m:t"
        );

        if ($node_list === false) {
            throw new RuntimeException(
                "text serach error ",
            );
        }

        $strings = [];

        foreach ($node_list as $element) {
            if (!$element instanceof DOMElement) {
                throw new RuntimeException(
                    "dom element not found.",
                );
            }

            $strings[] = (string)$element->nodeValue;
        }

        return $strings;
    }
}
