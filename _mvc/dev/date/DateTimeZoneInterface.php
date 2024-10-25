<?php

/**
*   DateInterface
*
*   @version 220225
*/

declare(strict_types=1);

namespace Concerto\date;

use DateTimeZone;

interface DateTimeZoneInterface
{
    /*
    *   offsetTime
    *
    *   @return int
    */
    public function offsetTime(): int;

    /*
    *   toDateTimezone
    *
    *   @return DateTimeZone
    */
    public function toDateTimezone(): DateTimeZone;

    /*
    *   From DateTimeZone
    */
    public function getName(): string;
}
