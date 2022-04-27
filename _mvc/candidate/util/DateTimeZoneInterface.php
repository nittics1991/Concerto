<?php

/**
*   DateInterface
*
*   @version 210922
*/

declare(strict_types=1);

namespace candidate\util;

interface DateTimeZoneInterface
{
    /*
    *   name
    *
    *   @return string
    */
    public function name(): string;

    /*
    *   offset
    *
    *   @return int
    */
    public function offset(): int;
}
