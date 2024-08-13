<?php

/**
*   OfficeParts
*
*   @version 240730
*/

declare(strict_types=1);

namespace dev\excel\parts;

use DOMDocument;
use DOMXPath;
use RuntimeException;
use dev\excel\ExcelArchive;

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
    }
}
