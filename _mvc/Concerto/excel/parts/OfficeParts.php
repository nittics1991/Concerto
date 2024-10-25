<?php

/**
*   OfficeParts
*
*   @version 240910
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use DOMDocument;
use DOMDocumentFragment;
use DOMElement;
use DOMNamedNodeMap;
use DOMNodeList;
use DOMText;
use DOMXPath;
use RuntimeException;
use Concerto\excel\{
    ExcelArchive,
    ExcelEventDispatcher,
};

abstract class OfficeParts
{
    /**
    *   @var string
    */
    protected string $file_path;

    /**
    *   @var string
    */
    protected string $namespace;

    /**
    *   @var ExcelArchive
    */
    protected ExcelArchive $archive;

    /**
    *   @var DOMDocument
    */
    protected DOMDocument $domDocument;

    /**
    *   @var DOMXPath
    */
    protected DOMXPath $xpath;

    /**
    *   @var ExcelEventDispatcher
    */
    protected ExcelEventDispatcher $eventDispatcher;

    /**
    *   __construct
    *
    *   @param ExcelArchive $archive
    */
    public function __construct(
        ExcelArchive $archive,
    ) {
        $this->archive = $archive;

        $this->domDocument = $this->archive->load(
            $this->file_path,
        );

        $this->xpath = new DOMXPath($this->domDocument);

        $this->xpath->registerNamespace(
            'm',
            $this->namespace,
        );

        $this->eventDispatcher = new ExcelEventDispatcher();
    }

    /**
    *   getFilePath
    *
    *   @return string
    */
    public function getFilePath(): string
    {
        return $this->file_path;
    }

    /**
    *   excelNodesToXml
    *
    *   @param ExcelNode[] $excelNodes
    *   @return string
    */
    protected function excelNodeToXml(
        array $excelNodes,
    ): string {
        $dom_string = '';

        foreach ($excelNodes as $excelNode) {
            $dom_string .= "<{$excelNode->name}";

            foreach (
                $excelNode->attribute as $attribute => $value
            ) {
                $dom_string .=
                    ' ' . $attribute . '="' . $value . '"';
            }

            if (isset($excelNode->text)) {
                $dom_string .=
                    '>' .
                    $excelNode->text .
                    "</{$excelNode->name}>";
                continue;
            }

            if ($excelNode->children === []) {
                $dom_string .= '/>';
                continue;
            }

            $dom_string .= '>';

            $dom_string .= $this->excelNodeToXml(
                $excelNode->children,
            ) . "</{$excelNode->name}>";
        }

        return $dom_string;
    }

    /**
    *   xmlToExcelNodes
    *
    *   @partam DOMNodeList|array $node_list
    *   @return ExcelNode[]
    */
    protected function xmlToExcelNodes(
        DOMNodeList|array $node_list,
    ): array {
        $excel_nodes = [];

        foreach ($node_list as $node) {
            $excelNode = new ExcelNode();

            $excelNode->name = $node->nodeName;

            $excelNode->attribute =
                $node->attributes === null ?
                [] :
                $this->domNamedNodeMapToArray(
                    $node->attributes
                );

            $filterd_children = [];

            foreach ($node->childNodes as $child_node) {
                if (
                    $child_node !== null &&
                    ! ($child_node instanceof DOMText)
                ) {
                    $filterd_children[] = $child_node;
                }
            }

            $children = $this->xmlToExcelNodes(
                $filterd_children,
            );

            $excelNode->children = $children;

            $excelNode->text = count($filterd_children) > 0 ?
                null : $node->nodeValue;

            $excel_nodes[] = $excelNode;
        }

        return $excel_nodes;
    }

    /**
    *   domNamedNodeMapToArray
    *
    *   @param DOMNamedNodeMap<\DOMNode> $iterator
    *   @return array<string, string>
    */
    protected function domNamedNodeMapToArray(
        DOMNamedNodeMap $iterator,
    ): array {
        $attributes = [];

        foreach ($iterator as $node) {
            if ($node->nodeValue !== null) {
                $attributes[$node->nodeName] =
                     $node->nodeValue;
            }
        }

        return $attributes;
    }

    /**
    *   domstringToFragment
    *
    *   @param string $dom_string
    *   @return DOMDocumentFragment
    */
    protected function domstringToFragment(
        string $dom_string,
    ): DOMDocumentFragment {
        $fragment = $this->domDocument
            ->createDocumentFragment();

        $loaded = $fragment->appendXML($dom_string);

        if ($loaded === false) {
            throw new RuntimeException(
                "fragment error:{$dom_string}",
            );
        }

        return $fragment;
    }

    /**
    *   queryXml
    *
    *   @praram string $xpath
    *   @return DOMElement
    */
    protected function queryXml(
        string $xpath,
    ): DOMElement {
        $node_list = $this->xpath->query($xpath);

        if (
            $node_list === false ||
            count($node_list) !== 1
        ) {
            throw new RuntimeException(
                "search error:{$xpath}",
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
}
