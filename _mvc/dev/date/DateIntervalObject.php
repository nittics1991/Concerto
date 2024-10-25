<?php

/**
*   DateIntervalObject
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date;

use Concerto\date\DateIntervalInterface;
use Concerto\date\implement\StandardDateIntervalTrait;

class DateIntervalObject implements DateIntervalInterface
{
    use StandardDateIntervalTrait;
}
