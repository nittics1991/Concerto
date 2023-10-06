<?php

/**
*   DateInterface
*
*   @version 210922
*/

declare(strict_types=1);

namespace candidate\util;

use DateTimeInterface;

interface DateInterface extends DateTimeInterface
{
    /*
    *   createFromFormat
    *
    *   @param string $format
    *   @param string $datetime
    *   @param ?DateTimezoneInterface $timezone
    *   @return DateInterface
    */
    public static function createFromFormat(
        string $format,
        string $datetime,
        ?DateTimezoneInterface $timezone,
    ): DateInterface;

    /*
    *   now
    *
    *   @return DateInterface
    */
    public static function now(): DateInterface;

    /*
    *   today
    *
    *   @return DateInterface
    */
    public static function today(): DateInterface;

    /*
    *   yeasterday
    *
    *   @return DateInterface
    */
    public static function yeasterday(): DateInterface;

    /*
    *   tomorrow
    *
    *   @return DateInterface
    */
    public static function tomorrow(): DateInterface;

    /*
    *   thisYear
    *
    *   @return DateInterface
    */
    public static function thisYear(): DateInterface;

    /*
    *   thisMonth
    *
    *   @return DateInterface
    */
    public static function thisMonth(): DateInterface;

    /*
    *   add
    *
    *   @param DateInterface $interval
    *   @return DateInterface
    */
    public function add(
        DateIntervalInterface $interval,
    ): DateInterface;

    /*
    *   addContext
    *
    *   @param string $datetime
    *   @return DateInterface
    */
    public function addContext(
        string $datetime,
    ): DateInterface;

    /*
    *   sub
    *
    *   @param DateInterface $interval
    *   @return DateInterface
    */
    public function sub(
        DateIntervalInterface $interval,
    ): DateInterface;

    /*
    *   subContext
    *
    *   @param string $datetime
    *   @return DateInterface
    */
    public function subContext(
        string $datetime,
    ): DateInterface;

    /*
    *   addYears
    *
    *   @param ?int $year
    *   @return DateInterface
    */
    public function addYears(
        ?int $year,
    ): DateInterface;

    /*
    *   addMonths
    *
    *   @param ?int $month
    *   @return DateInterface
    */
    public function addMonths(
        ?int $month,
    ): DateInterface;

    /*
    *   addWeeks
    *
    *   @param ?int $week
    *   @return DateInterface
    */
    public function addWeeks(
        ?int $week,
    ): DateInterface;

    /*
    *   addDays
    *
    *   @param ?int $day
    *   @return DateInterface
    */
    public function addDays(
        ?int $day,
    ): DateInterface;

    /*
    *   addHours
    *
    *   @param ?int $hour
    *   @return DateInterface
    */
    public function addHours(
        ?int $hour,
    ): DateInterface;

    /*
    *   addMinutes
    *
    *   @param ?int $minute
    *   @return DateInterface
    */
    public function addMinutes(
        ?int $minute,
    ): DateInterface;

    /*
    *   addSeconds
    *
    *   @param ?int $second
    *   @return DateInterface
    */
    public function addSeconds(
        ?int $second,
    ): DateInterface;

    /*
    *   subQuaters
    *
    *   @param ?int $quater
    *   @return DateInterface
    */
    public function subQuaters(
        ?int $quater,
    ): DateInterface;

    /*
    *   subYears
    *
    *   @param ?int $year
    *   @return DateInterface
    */
    public function subYears(
        ?int $year,
    ): DateInterface;

    /*
    *   subMonths
    *
    *   @param ?int $month
    *   @return DateInterface
    */
    public function subMonths(
        ?int $month,
    ): DateInterface;

    /*
    *   subWeeks
    *
    *   @param ?int $week
    *   @return DateInterface
    */
    public function subWeeks(
        ?int $week,
    ): DateInterface;

    /*
    *   subDays
    *
    *   @param ?int $day
    *   @return DateInterface
    */
    public function subDays(
        ?int $day,
    ): DateInterface;

    /*
    *   subHours
    *
    *   @param ?int $hour
    *   @return DateInterface
    */
    public function subHours(
        ?int $hour,
    ): DateInterface;

    /*
    *   subMinutes
    *
    *   @param ?int $minute
    *   @return DateInterface
    */
    public function subMinutes(
        ?int $minute,
    ): DateInterface;

    /*
    *   subSeconds
    *
    *   @param ?int $second
    *   @return DateInterface
    */
    public function subSeconds(
        ?int $second,
    ): DateInterface;

    /*
    *   nextQuater
    *
    *   @return DateInterface
    */
    public function nextQuater(): DateInterface;

    /*
    *   nextYear
    *
    *   @return DateInterface
    */
    public function nextYear(): DateInterface;

    /*
    *   nextMonth
    *
    *   @return DateInterface
    */
    public function nextMonth(): DateInterface;

    /*
    *   nextWeek
    *
    *   @return DateInterface
    */
    public function nextWeek(): DateInterface;

    /*
    *   nextDay
    *
    *   @return DateInterface
    */
    public function nextDay(): DateInterface;

    /*
    *   previousQuater
    *
    *   @return DateInterface
    */
    public function previousQuater(): DateInterface;

    /*
    *   previousYear
    *
    *   @return DateInterface
    */
    public function previousYear(): DateInterface;

    /*
    *   previousMonth
    *
    *   @return DateInterface
    */
    public function previousMonth(): DateInterface;

    /*
    *   previousWeek
    *
    *   @return DateInterface
    */
    public function previousWeek(): DateInterface;

    /*
    *   previousDay
    *
    *   @return DateInterface
    */
    public function previousDay(): DateInterface;

    /*
    *   modify
    *
    *   @param string $modifir
    *   @return DateInterface
    */
    public function modify(
        string $modifier,
    ): DateInterface;

    /*
    *   firstDayOfMonth
    *
    *   @return DateInterface
    */
    public function firstDayOfMonth(): DateInterface;

    /*
    *   lastDayOfMonth
    *
    *   @return DateInterface
    */
    public function lastDayOfMonth(): DateInterface;
    /*
    *   same
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function same(
        DateInterface $datetime,
    ): bool;

    /*
    *   eq
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function eq(
        DateInterface $datetime,
    ): bool;

    /*
    *   ne
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function ne(
        DateInterface $datetime,
    ): bool;

    /*
    *   gt
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function gt(
        DateInterface $datetime,
    ): bool;

    /*
    *   ge
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function ge(
        DateInterface $datetime,
    ): bool;

    /*
    *   lt
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function lt(
        DateInterface $datetime,
    ): bool;

    /*
    *   le
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function le(
        DateInterface $datetime,
    ): bool;

    /*
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array;

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
    *   week
    *
    *   @return int
    */
    public function week(): int;

    /*
    *   day
    *
    *   @return int
    */
    public function day(): int;

    /*
    *   hour
    *
    *   @return hour
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
    *   microsecond
    *
    *   @return int
    */
    public function microsecond(): int;

    /*
    *   timezone
    *
    *   @return DateTimezoneInterface
    */
    public function timezone(): DateTimezoneInterface;

    /*
    *   unixtime
    *
    *   @return int
    */
    public function unixtime(): int;
}
