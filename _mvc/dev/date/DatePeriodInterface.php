<?php

/**
*   DatePeriodInterface
*
*   @version 220225
*/

declare(strict_types=1);

namespace Concerto\date;

use Countable;
use DateInterval;
use DatePeriod;
use IteratorAggregate;
use Traversable;
use Concerto\date\{
    DateInterface,
    DateIntervalInterface,
};

interface DatePeriodInterface extends
    IteratorAggregate,
    Countable
{
    /*
    *   createFromDatePeriod
    *
    *   @param DatePeriod $period
    *   @return DatePeriodInterface
    */
    public static function createFromDatePeriod(
        DatePeriod $period,
    ): DatePeriodInterface;

    /*
    *   {inherid}
    */
    public function getIterator(): Traversable;

    /*
    *   {inherid}
    */
    public function count(): int;

    /*
    *   toDatePeriod
    *
    *   @return DatePeriod
    */
    public function toDatePeriod(): DatePeriod;

    /*
    *   From DatePeriod
    */
    public function startDate(): DateInterface;

    /*
    *   interval
    *
    *   @return DateIntervalInterface
    */
    public function interval(): DateIntervalInterface;

    /*
    *   From DatePeriod
    */
    public function endDate(): DateInterface;
}
