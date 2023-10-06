<?php

/**
*   DateInterface
*
*   @version 220225
*/

declare(strict_types=1);

namespace Concerto\date;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Concerto\date\{
    DateIntervalInterface,
    DateTimeZoneInterface,
};

interface DateInterface
{
    /*
    *   createFromInterface
    *
    *   @param DateTimeInterface $object
    *   @return DateInterface
    */
    public static function createFromInterface(
        DateTimeInterface $object,
    ): DateInterface;

    /*
    *   createFromFormat
    *
    *   @param string $format
    *   @param string $datetime
    *   @param ?DateTimeZoneInterface $timezone
    *   @return DateInterface
    */
    public static function createFromFormat(
        string $format,
        string $datetime,
        ?DateTimeZoneInterface $timezone,
    ): DateInterface;

    /*
    *   setFiscalStartMonth
    *
    *   @param ?int $month
    *   @return DateInterface
    */
    public function setFiscalStartMonth(
        ?int $month
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
    *   yesterday
    *
    *   @return DateInterface
    */
    public static function yesterday(): DateInterface;

    /*
    *   tomorrow
    *
    *   @return DateInterface
    */
    public static function tomorrow(): DateInterface;

    /*
    *   thisHalf
    *
    *   @param ?int $fiscal_start_month
    *   @return DateInterface
    */
    public static function thisHalf(
        ?int $fiscal_start_month,
    ): DateInterface;

    /*
    *   thisQuater
    *
    *   @param ?int $fiscal_start_month
    *   @return DateInterface
    */
    public static function thisQuater(
        ?int $fiscal_start_month,
    ): DateInterface;

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
    *   sub
    *
    *   @param DateInterface $interval
    *   @return DateInterface
    */
    public function sub(
        DateIntervalInterface $interval,
    ): DateInterface;

    /*
    *   addHalfs
    *
    *   @param ?int $half
    *   @return DateInterface
    */
    public function addHalfs(
        ?int $half,
    ): DateInterface;

    /*
    *   addQuaters
    *
    *   @param ?int $quater
    *   @return DateInterface
    */
    public function addQuaters(
        ?int $quater,
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
    *   subHalfs
    *
    *   @param ?int $half
    *   @return DateInterface
    */
    public function subHalfs(
        ?int $half,
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
    *   nextHalf
    *
    *   @return DateInterface
    */
    public function nextHalf(): DateInterface;

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
    *   previousHalf
    *
    *   @return DateInterface
    */
    public function previousHalf(): DateInterface;

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
    *   firstDayOfYear
    *
    *   @return DateInterface
    */
    public function firstDayOfYear(): DateInterface;

    /*
    *   firstDayOfHalf
    *
    *   @return DateInterface
    */
    public function firstDayOfHalf(): DateInterface;

    /*
    *   firstDayOfQuater
    *
    *   @return DateInterface
    */
    public function firstDayOfQuater(): DateInterface;

    /*
    *   firstDayOfMonth
    *
    *   @return DateInterface
    */
    public function firstDayOfMonth(): DateInterface;

    /*
    *   lastDayOfYear
    *
    *   @return DateInterface
    */
    public function lastDayOfYear(): DateInterface;

    /*
    *   lastDayOfHalf
    *
    *   @return DateInterface
    */
    public function lastDayOfHalf(): DateInterface;

    /*
    *   lastDayOfQuater
    *
    *   @return DateInterface
    */
    public function lastDayOfQuater(): DateInterface;

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
    *   different
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function different(
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
    *   between
    *       start <= date <= end
    *
    *   @param DateInterface $start
    *   @param DateInterface $end
    *   @return bool
    */
    public function between(
        DateInterface $start,
        DateInterface $end,
    ): bool;

    /*
    *   contain
    *       start < date < end
    *
    *   @param DateInterface $start
    *   @param DateInterface $end
    *   @return bool
    */
    public function contain(
        DateInterface $start,
        DateInterface $end,
    ): bool;

    /*
    *   overlap
    *       start <= date < end
    *
    *   @param DateInterface $start
    *   @param DateInterface $end
    *   @return bool
    */
    public function overlap(
        DateInterface $start,
        DateInterface $end,
    ): bool;

    /*
    *   sameYear
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function sameYear(
        DateInterface $datetime,
    ): bool;

    /*
    *   sameHalf
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function sameHalf(
        DateInterface $datetime,
    ): bool;

    /*
    *   sameQuater
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function sameQuater(
        DateInterface $datetime,
    ): bool;

    /*
    *   sameMonth
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function sameMonth(
        DateInterface $datetime,
    ): bool;

    /*
    *   sameDay
    *
    *   @param DateInterface $datetime
    *   @return bool
    */
    public function sameDay(
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
    *   half
    *
    *   @return int 0|1
    */
    public function half(): int;

    /*
    *   quater
    *
    *   @return int 0|1|2|3
    */
    public function quater(): int;

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
    *   @return DateTimeZoneInterface
    */
    public function timezone(): DateTimeZoneInterface;

    /*
    *   unixtime
    *
    *   @return int
    */
    public function unixtime(): int;

    /*
    *   toDateTime
    *
    *   @return DateTime
    */
    public function toDateTime(): DateTime;

    /*
    *   toDateTimeImmutable
    *
    *   @return DateTime
    */
    public function toDateTimeImmutable(): DateTimeImmutable;

    /*
    *   fiscalStartMonth
    *
    *   @return int
    */
    public function fiscalStartMonth(): int;

    /*
    *   except
    *
    *   @param DateInterface $targetObject
    *   @param bool $absolute
    *   @return DateIntervalInterface
    */
    public function except(
        DateInterface $targetObject,
        bool $absolute = false
    ): DateIntervalInterface;

    /*
    *   From DateTimeInterface
    */
    public function diff(
        DateTimeInterface $targetObject,
        bool $absolute = false
    ): DateInterval;

    /*
    *   From DateTimeInterface
    */
    public function format(
        string $format
    ): string;

    /*
    *   From DateTimeInterface
    */
    public function getOffset(): int;

    /*
    *   From DateTimeInterface
    */
    public function getTimestamp(): int|false;

    /*
    *   From DateTimeInterface
    */
    public function getTimezone(): DateTimeZone;
}
