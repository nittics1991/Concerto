<?php

/**
*   Printable
*
*   @version 180614
*/

declare(strict_types=1);

namespace Concerto\standard;

interface Printable
{
    /**
    *     出力
    *
    *   @return string
    */
    public function __toString();
}
