<?php

/**
*   DatePeriodObject
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date;

use Concerto\date\DatePeriodInterface;
use Concerto\date\implement\StandardDatePeriodTrait;

class DatePeriodObject implements DatePeriodInterface
{
    use StandardDatePeriodTrait;
}
