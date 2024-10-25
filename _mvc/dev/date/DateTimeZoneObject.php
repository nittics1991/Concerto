<?php

/**
*   DateTimeZoneObject
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date;

use Concerto\date\DateTimeZoneInterface;
use Concerto\date\implement\StandardDateTimeZoneTrait;

class DateTimeZoneObject implements DateTimeZoneInterface
{
    use StandardDateTimeZoneTrait;
}
