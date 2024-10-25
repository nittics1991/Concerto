<?php

/**
*   MbRegExOption
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

final class MbRegExOption
{
    /**
    *   @var string
    */
    public const IGNORE = 'i';
    public const EXTENDED = 'x';
    public const MULTILINE = 'm';
    public const ANCHOR = 's';
    public const MULTIANCHOR = 'p';
    public const LONGEST = 'l';
    public const EMPTY = 'n';
    public const EVAL = 'e';

    /**
    *   optionString
    *
    *   @param string[] $options
    *   @return string
    */
    public static function optionString(
        array $options,
    ): string {
        return implode('', $options);
    }
}
