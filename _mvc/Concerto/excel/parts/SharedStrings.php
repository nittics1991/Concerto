<?php

/**
*   SharedStrings
*
*   @version 240910
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;
use RuntimeException;
use Concerto\excel\{
    ExcelArchive,
    ExcelEventDispatcher,
};
use Concerto\excel\parts\OfficeParts;
use Concerto\excel\exception\ExcelArchveLoadException;

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
    *   @var string[]
    */
    protected array $strings = [];

    /**
    *   @var ?int
    */
    protected ?int $last_position = null;

    /**
    *   @var string[]
    */
    protected array $add_strings = [];

    /**
    *   @var bool
    */
    protected bool $dirty = false;

    /**
    *   @var bool
    */
    protected bool $isCreated = false;

    /**
    *   {inheritDoc}
    */
    public function __construct(
        ExcelArchive $archive,
    ) {
        $this->archive = $archive;

        //SharedStrings.xmlは存在しない場合がある
        try {
            $this->domDocument = $this->archive->load(
                $this->file_path,
            );
        } catch (ExcelArchveLoadException $e) {
            $this->domDocument = $this->createDomDocument();
        }

        $this->xpath = new DOMXPath($this->domDocument);

        $this->xpath->registerNamespace(
            'm',
            $this->namespace,
        );

        $this->eventDispatcher = new ExcelEventDispatcher();

        $this->readAllString();
    }

    /**
    *   createDomDocument
    *
    *   @return DOMDocument
    */
    private function createDomDocument(): DOMDocument
    {
        $dom_string =
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' .
            "\r\n" .
            '<sst xmlns="' .
            $this->namespace .
            '"/>';

        $domDocument = new DOMDocument();

        $result = $domDocument->loadXML($dom_string);

        if ($result === false) {
            throw new RuntimeException(
                "DOMDocument create error",
            );
        }

        $this->isCreated = true;

        return $domDocument;
    }

    /**
    *   readAllString
    *
    *   @return void
    */
    private function readAllString(): void
    {
        $node_list = $this->xpath->query(
            "//m:si"
        );

        if ($node_list === false) {
            return;
        }

        $strings = [];

        foreach ($node_list as $element) {
            $strings[] = $this->extractText(
                $element->childNodes,
            );
        }

        $this->strings = $strings;

        $this->last_position = $this->strings === [] ?
            null : count($this->strings) - 1;
    }

    /**
    *   extractText
    *
    *   @param DOMNodeList<DOMElement> $node_list
    *   @return string
    */
    private function extractText(
        DOMNodeList $node_list,
    ): string {
        $strings = [];

        foreach ($node_list as $element) {
            switch ($element->tagName) {
                case 't':
                    $strings[] = $element->textContent;
                    break;
                case 'r':
                    $strings[] = $this->extractText(
                        $element->childNodes,
                    );
                    break;
            }
        }

        return implode('', $strings);
    }

    /**
    *   stringNo
    *
    *   @param string $value
    *   @return ?int
    */
    public function stringNo(
        string $value,
    ): ?int {
        $pos = array_search(
            $value,
            $this->strings,
            true,
        );

        if ($pos !== false) {
            return (int)$pos;
        }

        $pos = array_search(
            $value,
            $this->add_strings,
            true,
        );

        if ($pos !== false) {
            return $this->last_position + (int)$pos + 1;
        }

        return null;
    }

    /**
    *   hasString
    *
    *   @param string $string
    *   @return bool
    */
    public function hasString(
        string $string,
    ): bool {
        return $this->stringNo($string) !== null;
    }

    /**
    *   addString
    *
    *   @param string $string
    *   @return int
    */
    public function addString(
        string $string,
    ): int {
        $string_no = $this->stringNo($string);

        if ($string_no !== null) {
            return $string_no;
        }

        $this->add_strings[] = $string;

        $this->dirty = true;

        if ($this->last_position === null) {
            return count($this->add_strings) - 1;
        }

        return $this->last_position +
            count($this->add_strings);
    }

    /**
    *   close
    *
    *   @return void
    */
    public function close(): void
    {
        if ($this->dirty) {
            $this->addData();
        }
    }

    /**
    *   addData
    *
    *   @return void
    */
    private function addData(): void
    {
        $dom_string = $this->dataToXml();

        $target = $this->queryXml('//m:sst');

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

        if ($this->isCreated) {
            $this->eventDispatcher->dispatch(
                $this,
                __METHOD__ . '.create',
            );
            $this->isCreated = false;
        }
    }

    /**
    *   dataToXml
    *
    *   @return string
    */
    private function dataToXml(): string
    {
        $dom_string = '';

        foreach ($this->add_strings as $string) {
            $dom_string .=
                '<si><t xml:space="preserve">' .
                htmlentities(
                    $string,
                    ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1,
                ) .
            '</t></si>';
        }

        $this->strings += $this->add_strings;

        $this->last_position = count($this->strings) - 1;

        $this->add_strings = [];

        $this->dirty = false;

        return $dom_string;
    }

    /**
    *   findByString
    *
    *   @param int $no
    *   @return ?string
    */
    public function findBySharedString(
        int $no,
    ): ?string {
        if ($this->dirty) {
            $this->addData();
        }

        return isset($this->strings[$no]) ?
            $this->strings[$no] : null;
    }
}
