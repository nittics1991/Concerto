<?php

/**
*   DateIntervalInterface
*
*   @version 220225
*/

declare(strict_types=1);

namespace Concerto\date;

use DateInterval;

interface DateIntervalInterface
{
    /*
    *   createFromDateInterval
    *
    *   @param DateInterval $interval
    *   @return DateIntervalInterface
    */
    public static function createFromDateInterval(
        DateInterval $interval
    ): DateIntervalInterface;

    /*
    *   createFromDateString
    *
    *   @param string $datetime
    *   @return DateIntervalInterface
    */
    public static function createFromDateString(
        string $datetime
    ): DateIntervalInterface;

    /*
    *   format
    *
    *   @param string $format
    *   @return string
    */
    public function format(
        string $format
    ): string;

    /*
    *   year
    *
    *   @return int
    */
    public function year(): int;

    /*
    *   month
    *
    *   @return int
    */
    public function month(): int;

    /*
    *   day
    *
    *   @return int
    */
    public function day(): int;

    /*
    *   hour
    *
    *   @return int
    */
    public function hour(): int;

    /*
    *   minute
    *
    *   @return int
    */
    public function minute(): int;

    /*
    *   second
    *
    *   @return int
    */
    public function second(): int;

    /*
    *   {inherit}
    */
    public function milliSecond(): float;

    /*
    *   {inherit}
    */
    public function microSecond(): float;

    /*
    *   {inherit}
    */
    public function days(): int;

    /*
    *   toDateInterval
    *
    *   @return DateInterval
    */
    public function toDateInterval(): DateInterval;
}
