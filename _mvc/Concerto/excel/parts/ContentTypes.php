<?php

/**
*   ContentTypes
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel\parts;

use RuntimeException;
use Concerto\excel\parts\{
    OfficeParts,
    SharedStrings
};

class ContentTypes extends OfficeParts
{
    /**
    *   {inheritDoc}
    */
    protected string $file_path =
        '[Content_Types].xml';

    /**
    *   {inheritDoc}
    */
    protected string $namespace =
        'http://schemas.openxmlformats.org/package/2006/content-types';

    /**
    *   @var array<string,string>
    */
    protected array $content_types = [
        SharedStrings::class =>
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml',
    ];

    /**
    *   addPartName
    *
    *   @param string $file_path
    *   @param class-string $contenttype
    *   @return void
    */
    public function addPartName(
        string $file_path,
        string $contenttype,
    ): void {
        if (!isset($this->content_types[$contenttype])) {
            throw new RuntimeException(
                "content type not defined:{$contenttype}"
            );
        }

        $type = $this->content_types[$contenttype];

        $dom_string =
            '<Override PartName="' .
            $file_path .
            '" ContentType="' .
            $type .
            '" />';

        $target = $this->queryXml('//m:Types');

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
}
