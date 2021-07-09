<?php

/**
*   MarkdownConverterInterface
*
*   @version 200916
*/

declare(strict_types=1);

namespace Concerto\markdown;

interface MarkdownConverterInterface
{
    /**
    *   変換
    *
    *   @param string $contents
    *   @return string
    */
    public function convert(string $contents): string;
}
