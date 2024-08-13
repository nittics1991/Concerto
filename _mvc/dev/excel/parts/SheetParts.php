<?php

/**
*   SheetParts
*
*   @version 240730
*/

declare(strict_types=1);

namespace dev\excel\parts;

use DateTimeImmutable;
use DateTimeInterface;
use DOMElement;
use DOMDocumentFragment;
use RuntimeException;
use dev\excel\{
    ExcelAddress,
    ExcelArchive,
};
use dev\excel\parts\{
    OfficeParts,
    SharedStrings,
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
    *   @var SharedStrings
    */
    private SharedStrings $sharedStrings;

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
    *   save
    *
    *   @param array<array<int|float|string|DateTimeInterface>> $data
    *   @return void
    */
    public function save(
        array $data,
    ): void {
        $dom_string = '';

        foreach ($data as $row_no => $row) {
            $dom_string .= $this->createRowData(
                $row_no,
                $row
            );
        }

        $this->writeDomString(
            $dom_string
        );

        $this->archive->save(
            $this->file_path,
            $this->domDocument,
        );
    }

    /**
    *   createRowData
    *
    *   @param int $row_no
    *   @param array<int|float|string|DateTimeInterface> $row
    *   @return string
    */
    private function createRowData(
        int $row_no,
        array $row,
    ): string {
        $dom_string = '<row r="' . $row_no . '">';

        foreach ($row as $column_no => $column) {
            $dom_string .= $this->createCellData(
                $row_no,
                $column_no,
                $column,
            );
        }

        $dom_string .= '</row>';

        return $dom_string;
    }

    /**
    *   createCellData
    *
    *   @param int $row_no
    *   @param int $column_no
    *   @param mixed $column
    *   @return string
    */
    private function createCellData(
        int $row_no,
        int $column_no,
        mixed $column,
    ): string {
        if ($column === null) {
            return '';
        }

        $address = ExcelAddress::locationToAddress(
            [$row_no, $column_no],
        );

        if ($column instanceof DateTimeInterface) {
            return '<c r="' . $address . '" t="d">' .
                '<v>' .
                $column->format(
                    DateTimeInterface::ATOM,
                ) .
                '</v>' .
                '</c>';
        } elseif (is_int($column) || is_float($column)) {
            return '<c r="' . $address . '" t="n">' .
                '<v>' . $column . '</v>' .
                '</c>';
        } elseif (is_string($column)) {
            return '<c r="' . $address . '" t="inlineStr">' .
                '<is><t xml:space="preserve">' . htmlentities(
                    $column,
                    ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1,
                ) .
                '</t></is>' .
                '</c>';
        }

        throw new RuntimeException(
            "data type error:{$address}",
        );
    }

    /**
    *   writeDomString
    *
    *   @param string $dom_string
    *   @return DOMElement
    */
    private function writeDomString(
        string $dom_string,
    ): DOMElement {
        $target = $this->getSheetDataElement();

        if (strlen($dom_string) > 0) {
            $fragment = $this->createImportNode($dom_string);

            $target->appendChild($fragment);
        }
        
        return $target;
    }

    /**
    *   getSheetDataElement
    *
    *   @return DOMElement
    */
    private function getSheetDataElement(): DOMElement
    {
        $node_list = $this->xpath->query("//m:sheetData");

        if (
            $node_list === false ||
            count($node_list) !== 1
        ) {
            throw new RuntimeException(
                "sheetData element search error",
            );
        }

        $element = $node_list->item(0);

        if (!$element instanceof DOMElement) {
            throw new RuntimeException(
                "dom element get error",
            );
        }

        return $element;
    }

    /**
    *   createImportNode
    *
    *   @param string $dom_string
    *   @return DOMDocumentFragment
    */
    private function createImportNode(
        string $dom_string,
    ): DOMDocumentFragment {
        $fragment = $this->domDocument
            ->createDocumentFragment();

        $loaded = $fragment->appendXML($dom_string);

        if ($loaded === false) {
            throw new RuntimeException(
                "sheetX.xml load error:{$this->file_path}",
            );
        }

        return $fragment;
    }

    /**
    *   loadData
    *
    *   @return array<array<int|float|string|DateTimeInterface>>
    */
    public function loadData(): array
    {
        $node_list = $this->xpath->query("//m:sheetData");

        if ($node_list === false) {
            throw new RuntimeException(
                "sheetData element search error",
            );
        }

        if (count($node_list) === 0) {
            return [];
        }

        $values = $this->getValues();

        [$addresses, $types] = $this->getAddressAndTypes();
        
        //shredstrings.xmlは存在しない場合がある
        try {
            $this->sharedStrings = $this->SharedStrings ??
                new SharedStrings($this->archive);
        } catch (RuntimeException $e) {
            
        }

        $strings = isset($this->sharedStrings)?
            $this->sharedStrings->getAllString():
            [];

        return $this->convertCellData(
            $addresses,
            $types,
            $values,
            $strings,
        );
    }

    /**
    *   getValues
    *
    *   @return string[]
    */
    private function getValues(): array
    {
        $node_list = $this->xpath->query(
            "//m:v"
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

    /**
    *   getAddressAndTypes
    *
    *   @return array{0:string[],1:string[]}
    *       [$addresses, $types]
    */
    private function getAddressAndTypes(): array
    {
        $node_list = $this->xpath->query(
            "//m:c"
        );

        if ($node_list === false) {
            throw new RuntimeException(
                "cell serach error ",
            );
        }

        $addresses = [];
        $types = [];

        foreach ($node_list as $element) {
            if (!$element instanceof DOMElement) {
                throw new RuntimeException(
                    "dom element not found.",
                );
            }

            $addresses[] = $element->getAttribute('r');
            $types[] = $element->getAttribute('t');
        }

        return [$addresses, $types];
    }

    /**
    *   convertCellData
    *
    *   @param string[] $addresses
    *   @param string[] $types
    *   @param string[] $values
    *   @param string[] $strings
    *   @return array<array<int|float|string|DateTimeInterface>>
    */
    private function convertCellData(
        array $addresses,
        array $types,
        array $values,
        array $strings,
    ): array {
        $data = [];

        array_map(
            function (
                string $address,
                string $type,
                string $value,
            ) use (
                &$data,
                $strings,
            ) {
                $location = ExcelAddress::addressToLocation(
                    $address,
                );

                $val = match ($type) {
                    'b' => $value,
                    'd' => new DateTimeImmutable($value),
                    'e' => $value,
                    'inlineStr' => $value,
                    'n' => $this->strToNumber($value),
                    's' => isset($strings[$value]) ?
                        $strings[$value] : '',
                    'str' => $value,
                    '' => $this->strToNumber($value),
                    default => throw new RuntimeException(
                        "data type error:{$address}",
                    ),
                };

                $data[$location[0]][$location[1]] = $val;

                return;
            },
            $addresses,
            $types,
            $values,
        );
        
        return $data;
    }
    
    /**
    *   strToNumber
    *
    *   @param string $value
    *   @return int|float
    */
    private function strToNumber(
        string $value,
    ): int|float {
        return strpos(strval($value), '.') === false?
        intval($value):
        floatval($value);
    }
}
