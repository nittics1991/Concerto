<?php

/**
*   MarkdownConverter
*
*   @version 200916
*   @see https://github.com/erusev/parsedown
*/

declare(strict_types=1);

namespace Concerto\markdown;

use Parsedown;

class MarkdownConverter implements MarkdownConverterInterface
{
    /**
    *   converter
    *
    *   @var Parsedown
    */
    private $converter;

    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->converter = (new Parsedown())
            ->setMarkupEscaped(true)
            ->setBreaksEnabled(true);
    }

    /**
    *   {inherit}
    *
    *   @param string $contents
    *   @return string
    */
    public function convert(string $contents): string
    {
        return $this->converter->text($contents);
    }
}
