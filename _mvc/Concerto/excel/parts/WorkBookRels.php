<?php

/**
*   WorkBookRels
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use DOMElement;
use RuntimeException;
use Concerto\excel\parts\{
    OfficeParts,
    SharedStrings,
};

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
    *   @var array<string,string>
    */
    protected array $relationship_types = [
        SharedStrings::class =>
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings',
    ];

    /**
    *   findSheetFileName
    *
    *   @param string $sheet_id
    *   @return string
    */
    public function findSheetFileName(
        string $sheet_id,
    ): string {
        $element = $this->queryXml(
            "//m:Relationship[@Id='{$sheet_id}']",
        );

        $attribute = $element->getAttribute('Target');

        if ($attribute === '') {
            throw new RuntimeException(
                "target not found:{$sheet_id}",
            );
        }

        return $attribute;
    }

    /**
    *   addRelationship
    *
    *   @param string $target
    *   @param string $relation_type
    *   @return void
    */
    public function addRelationship(
        string $target,
        string $relation_type,
    ): void {
        if (!isset($this->relationship_types[$relation_type])) {
            throw new RuntimeException(
                "content type not defined:{$relation_type}"
            );
        }

        $type = $this->relationship_types[$relation_type];

        $id = $this->createNewRelationId();

        $dom_string =
            '<Relationship Id="' .
            $id .
            '" Type="' .
            $type .
            '" Target="' .
            $target .
            '" />';

        $target = $this->queryXml('//m:Relationships');

        $fragment = $this->domstringToFragment(
            $dom_string
        );

        $target->appendChild($fragment);

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
    *   getMaxRelationId
    *
    *   @return string
    */
    private function getMaxRelationId(): string
    {
        $node_list = $this->xpath->query('//m:Relationship');

        if ($node_list === false) {
            throw new RuntimeException(
                "relathionship element search error",
            );
        }

        $ids = [];
        $matches = [];

        foreach ($node_list as $element) {
            if (!$element instanceof DOMElement) {
                throw new RuntimeException(
                    "dom element get error",
                );
            }

            $id = $element->attributes
                ->getNamedItem('Id');

            if ($id === null) {
                throw new RuntimeException(
                    "Id attribute not defined",
                );
            }

            mb_ereg(
                'Id="([^"]*)"',
                $id->textContent,
                $matches
            );

            $ids[] = $id->textContent;
        }

        rsort($ids, SORT_NATURAL);

        reset($ids);

        $max_id = current($ids);

        return empty($ids) || $max_id === false ?
            '' : $max_id;
    }

    /**
    *   createNewRelationId
    *
    *   @return string
    */
    private function createNewRelationId(): string
    {
        $max_id = $this->getMaxRelationId();

        if ($max_id === '') {
            return 'rId1';
        }

        $pos = mb_strpos($max_id, 'rId');

        if ($pos !== 0) {
            throw new RuntimeException(
                "invalid relashonship id format:{$max_id}",
            );
        }

        $no = mb_substr($max_id, 3);

        if (
            $no === '' ||
            mb_ereg_match('^[0-9]+$', $no) === false
        ) {
            throw new RuntimeException(
                "invalid relashonship id number format:{$no}",
            );
        }

        return 'rId' . strval((intval($no) + 1));
    }
}
