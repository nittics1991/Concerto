<?php

/**
*   MbRegExMode
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

final class MbRegExMode
{
    /**
    *   @var string
    */
    public const JAVA = 'r';
    public const GNU = 'u';
    public const GREP = 'g';
    public const EMACS = 'c';
    public const RUBY = 'r';
    public const PERL = 'z';
    public const POSIX = 'b';
    public const EXPOSIX = 'd';
}
