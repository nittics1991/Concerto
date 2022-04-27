<?php

/**
*   MarkdownConverter
*
*   @version 220122
*   @see https://github.com/erusev/parsedown
*/

declare(strict_types=1);

namespace candidate\markdown;

use Parsedown;
use candidate\markdown\MarkdownConverterInterface;

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
