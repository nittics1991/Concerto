<?php

/**
*   StandardDateTrait
*
*   @version 220226
*/

declare(strict_types=1);

namespace Concerto\date\implement;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use RuntimeException;
use Concerto\date\{
    DateInterface,
    DateIntervalInterface,
    DateTimeZoneInterface,
};
use Concerto\date\{
    DateIntervalObject,
    DateTimeZoneObject,
};

trait StandardDateTrait
{
    /*
    *   @var int
    */
    protected static int $default_fiscal_start_month = 4;

    /*
    *   @var DateTimeImmutable
    */
    protected DateTimeImmutable $datetime;

    /*
    *   @var int
    */
    protected int $fiscal_start_month = 4;

    /*
    *   __construct
    *
    *   @param ?string $datetime
    *   @param ?DateTimeZoneInterface $timezone
    *   @param ?int $fiscal_start_month
    */
    public function __construct(
        ?string $datetime = 'now',
        ?DateTimeZoneInterface $timezone = null,
        ?int $fiscal_start_month = 4,
    ) {
        $this->setFiscalStartMonth($fiscal_start_month);

        $resolved_timezone = isset($timezone) ?
            $timezone->toDateTimezone() :
            new DateTimeZone(
                date_default_timezone_get(),
            );

        $this->datetime = new DateTimeImmutable(
            $datetime ?? 'now',
            $resolved_timezone,
        );
    }

    /*
    *   {inherit}
    */
    public static function createFromInterface(
        DateTimeInterface $object,
    ): DateInterface {
        $datetime = DateTimeImmutable::createFromInterface(
            $object,
        );

        return new self(
            $datetime->format(
                DateTimeInterface::ATOM,
            ),
            new DateTimeZoneObject(
                $datetime->getTimezone()->getName(),
            ),
        );
    }

    /*
    *   {inherit}
    */
    public static function createFromFormat(
        string $format,
        string $datetime,
        ?DateTimeZoneInterface $timezone = null,
    ): DateInterface {
        if (!mb_ereg_match('!', $format)) {
            $format = "!{$format}";
        }

        $resolved_timezone = isset($timezone) ?
            $timezone->toDateTimezone() :
            new DateTimeZone(
                date_default_timezone_get(),
            );

        $created_date = DateTimeImmutable::createFromFormat(
            $format,
            $datetime,
            $resolved_timezone,
        );

        if ($created_date === false) {
            throw new InvalidArgumentException(
                "invalid argument",
            );
        }

        return static::createFromInterface(
            $created_date,
        );
    }

    /*
    *   {inherit}
    */
    public function setFiscalStartMonth(
        ?int $month
    ): DateInterface {
        $month = $month ?? static::$default_fiscal_start_month;

        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException(
                "required 1 to 12"
            );
        }
        $this->fiscal_start_month = $month;
        return $this;
    }

    /*
    *   {inherit}
    */
    public static function now(): DateInterface
    {
        return new self('now');
    }

    /*
    *   {inherit}
    */
    public static function today(): DateInterface
    {
        return new self('today');
    }

    /*
    *   {inherit}
    */
    public static function yesterday(): DateInterface
    {
        return new self('yesterday');
    }

    /*
    *   {inherit}
    */
    public static function tomorrow(): DateInterface
    {
        return new self('tomorrow');
    }

    /*
    *   {inherit}
    */
    public static function thisHalf(
        ?int $fiscal_start_month,
    ): DateInterface {
        $today = static::today();
        $today = $today->setFiscalStartMonth(
            $fiscal_start_month,
        );

        return static::toHalf(
            $today,
            $fiscal_start_month,
        );
    }

    /*
    *   {inherit}
    */
    protected static function toHalf(
        DateInterface $datetime,
        ?int $fiscal_start_month,
    ): DateInterface {
        $datetime = $datetime->setFiscalStartMonth(
            $fiscal_start_month,
        );

        $half_start = static::createFromFormat(
            '!Yn',
            (string)$datetime->year() .
                (string)$datetime->fiscalStartMonth(),
        );
        $half_start = $half_start->setFiscalStartMonth(
            $fiscal_start_month
        );

        $next_half_start = $half_start->addMonths(6);
        $previous_half_start = $half_start->subMonths(6);

        if ($datetime->ge($next_half_start)) {
            return $next_half_start;
        }

        if ($datetime->ge($half_start)) {
            return $half_start;
        }

        if ($datetime->lt($previous_half_start)) {
            return $previous_half_start->subMonths(6);
        }

        return $previous_half_start;
    }

    /*
    *   {inherit}
    */
    public static function thisQuater(
        ?int $fiscal_start_month,
    ): DateInterface {
        $today = static::today();
        return static::toQuater(
            $today,
            $fiscal_start_month,
        );
    }

    /*
    *   toQuater
    *
    *   @param DateInterface $datetime
    *   @param ?int $fiscal_start_month
    *   @return DateInterface
    */
    protected static function toQuater(
        DateInterface $datetime,
        ?int $fiscal_start_month,
    ): DateInterface {
        $datetime = $datetime->setFiscalStartMonth(
            $fiscal_start_month
        );

        $quater_start = static::createFromFormat(
            '!Yn',
            (string)$datetime->year() .
                (string)$datetime->fiscalStartMonth(),
        );
        $quater_start = $quater_start->setFiscalStartMonth(
            $fiscal_start_month
        );

        $next_quater_start =
            $quater_start->addMonths(3);
        $next_to_next_quater_start =
            $next_quater_start->addMonths(3);

        $previous_quater_start =
            $quater_start->subMonths(3);
        $previous_to_previous_quater_start =
            $previous_quater_start->subMonths(3);

        if ($datetime->ge($next_to_next_quater_start)) {
            return $next_to_next_quater_start;
        }

        if ($datetime->ge($next_quater_start)) {
            return $next_quater_start;
        }

        if ($datetime->lt($previous_quater_start)) {
            return $previous_to_previous_quater_start;
        }

        if ($datetime->lt($quater_start)) {
            return $previous_quater_start;
        }

        return $quater_start;
    }

    /*
    *   {inherit}
    */
    public static function thisYear(): DateInterface
    {
        return new self('this year');
    }

    /*
    *   {inherit}
    */
    public static function thisMonth(): DateInterface
    {
        return new self('this month');
    }

    /*
    *   datetimeToString
    *
    *   @param DateTimeInterface $date
    *   @return string
    */
    protected function datetimeToString(
        DateTimeInterface $date,
    ): string {
        return $date->format(
            DateTimeInterface::ATOM,
        );
    }

    /*
    *   {inherit}
    */
    public function add(
        DateIntervalInterface $interval,
    ): DateInterface {
        $result = $this->datetime->add(
            $interval->toDateInterval(),
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   {inherit}
    */
    public function sub(
        DateIntervalInterface $interval,
    ): DateInterface {
        $result = $this->datetime->sub(
            $interval->toDateInterval(),
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   addDuration
    *
    *   @param int $duration
    *   @param string $designator
    *   @param ?bool $isTime
    *   @return DateInterface
    */
    protected function addDuration(
        int $duration,
        string $designator,
        ?bool $isTime = false,
    ): DateInterface {
        return $duration < 0 ?
            $this->subDuration(
                abs($duration),
                $designator,
                $isTime,
            ) :
            new $this(
                $this->add(
                    new DateIntervalObject(
                        ($isTime ? 'PT' : 'P') .
                        "{$duration}{$designator}"
                    ),
                )->format(
                    DateTimeInterface::ATOM,
                ),
                $this->timezone(),
                $this->fiscal_start_month,
            );
    }

    /*
    *   subDuration
    *
    *   @param int $duration
    *   @param string $designator
    *   @param ?bool $isTime
    *   @return DateInterface
    */
    protected function subDuration(
        int $duration,
        string $designator,
        ?bool $isTime = false,
    ): DateInterface {
        return $duration < 0 ?
            $this->addDuration(
                abs($duration),
                $designator,
                $isTime,
            ) :
            new $this(
                $this->sub(
                    new DateIntervalObject(
                        ($isTime ? 'PT' : 'P') .
                        "{$duration}{$designator}"
                    ),
                )->format(
                    DateTimeInterface::ATOM,
                ),
                $this->timezone(),
                $this->fiscal_start_month,
            );
    }

    /*
    *   {inherit}
    */
    public function addHalfs(
        ?int $half,
    ): DateInterface {
        return $this->addDuration(
            ($half ?? 0) * 6,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function addQuaters(
        ?int $quater
    ): DateInterface {
        return $this->addDuration(
            ($quater ?? 0) * 3,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function addYears(
        ?int $year = 1,
    ): DateInterface {
        return $this->addDuration(
            $year ?? 0,
            'Y',
        );
    }

    /*
    *   {inherit}
    */
    public function addMonths(
        ?int $month,
    ): DateInterface {
        return $this->addDuration(
            $month ?? 0,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function addWeeks(
        ?int $week,
    ): DateInterface {
        return $this->addDuration(
            $week ?? 0,
            'W',
        );
    }

    /*
    *   {inherit}
    */
    public function addDays(
        ?int $day,
    ): DateInterface {
        return $this->addDuration(
            $day ?? 0,
            'D',
        );
    }

    /*
    *   {inherit}
    */
    public function addHours(
        ?int $hour,
    ): DateInterface {
        return $this->addDuration(
            $hour ?? 0,
            'H',
            true,
        );
    }

    /*
    *   {inherit}
    */
    public function addMinutes(
        ?int $minute,
    ): DateInterface {
        return $this->addDuration(
            $minute ?? 0,
            'M',
            true,
        );
    }

    /*
    *   {inherit}
    */
    public function addSeconds(
        ?int $second,
    ): DateInterface {
        return $this->addDuration(
            $second ?? 0,
            'S',
            true,
        );
    }

    /*
    *   {inherit}
    */
    public function subHalfs(
        ?int $half,
    ): DateInterface {
        return $this->subDuration(
            ($half ?? 0) * 6,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function subQuaters(
        ?int $quater,
    ): DateInterface {
        return $this->subDuration(
            ($quater ?? 0) * 3,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function subYears(
        ?int $year,
    ): DateInterface {
        return $this->subDuration(
            $year ?? 0,
            'Y',
        );
    }

    /*
    *   {inherit}
    */
    public function subMonths(
        ?int $month,
    ): DateInterface {
        return $this->subDuration(
            $month ?? 0,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function subWeeks(
        ?int $week,
    ): DateInterface {
        return $this->subDuration(
            $week ?? 0,
            'W',
        );
    }

    /*
    *   {inherit}
    */
    public function subDays(
        ?int $day,
    ): DateInterface {
        return $this->subDuration(
            $day ?? 0,
            'D',
        );
    }

    /*
    *   {inherit}
    */
    public function subHours(
        ?int $hour,
    ): DateInterface {
        return $this->subDuration(
            $hour ?? 0,
            'H',
            true,
        );
    }

    /*
    *   {inherit}
    */
    public function subMinutes(
        ?int $minute,
    ): DateInterface {
        return $this->subDuration(
            $minute ?? 0,
            'M',
            true,
        );
    }

    /*
    *   {inherit}
    */
    public function subSeconds(
        ?int $second,
    ): DateInterface {
        return $this->subDuration(
            $second ?? 0,
            'S',
            true,
        );
    }

    /*
    *   {inherit}
    */
    public function nextHalf(): DateInterface
    {
        return $this->addDuration(
            6,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function nextQuater(): DateInterface
    {
        return $this->addDuration(
            3,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function nextYear(): DateInterface
    {
        return $this->addDuration(
            1,
            'Y',
        );
    }

    /*
    *   {inherit}
    */
    public function nextMonth(): DateInterface
    {
        return $this->addDuration(
            1,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function nextWeek(): DateInterface
    {
        return $this->addDuration(
            1,
            'W',
        );
    }

    /*
    *   {inherit}
    */
    public function nextDay(): DateInterface
    {
        return $this->addDuration(
            1,
            'D',
        );
    }

    /*
    *   {inherit}
    */
    public function previousHalf(): DateInterface
    {
        return $this->subDuration(
            6,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function previousQuater(): DateInterface
    {
        return $this->subDuration(
            3,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function previousYear(): DateInterface
    {
        return $this->subDuration(
            1,
            'Y',
        );
    }

    /*
    *   {inherit}
    */
    public function previousMonth(): DateInterface
    {
        return $this->subDuration(
            1,
            'M',
        );
    }

    /*
    *   {inherit}
    */
    public function previousWeek(): DateInterface
    {
        return $this->subDuration(
            1,
            'W',
        );
    }

    /*
    *   {inherit}
    */
    public function previousDay(): DateInterface
    {
        return $this->subDuration(
            1,
            'D',
        );
    }

    /*
    *   {inherit}
    */
    public function modify(
        string $modifier,
    ): DateInterface {
        $result = $this->datetime->modify(
            $modifier
        );

        if ($result === false) {
            throw new InvalidArgumentException(
                "invalid modifier:{$modifier}"
            );
        }

        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   createDayOf
    *
    *   @param string $ordinal
    *   @param string $month_string
    *   @return DateInterface
    */
    protected function createDayOf(
        string $ordinal,
        string $month_string,
    ): DateInterface {
        $result = $this->datetime->modify(
            "{$ordinal} day of {$month_string} midnight"
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   monthStringDayOfHalf
    *
    *   @return string
    */
    protected function monthStringDayOfHalf(): string
    {
        $half_start = static::toHalf(
            $this,
            $this->fiscal_start_month,
        );

        $half_month_no =
            $half_start->month();

        $half_month = DateTimeImmutable::createFromFormat(
            'Y-n-d h:i:s',
            "2000-{$half_month_no}-1 00:00:00",
            $this->getTimeZone(),
        );

        if ($half_month === false) {
            throw new RuntimeException(
                "fairure get month name",
            );
        }

        return $half_month->format('F');
    }

    /*
    *   monthStringDayOfQuater
    *
    *   @return string
    */
    protected function monthStringDayOfQuater(): string
    {
        $quater_start = static::toQuater(
            $this,
            $this->fiscal_start_month,
        );

        $quater_month_no =
            $quater_start->month();

        $quater_month = DateTimeImmutable::createFromFormat(
            'Y-n-d h:i:s',
            "2000-{$quater_month_no}-1 00:00:00",
            $this->getTimeZone(),
        );

        if ($quater_month === false) {
            throw new RuntimeException(
                "fairure get month name",
            );
        }

        return $quater_month->format('F');
    }

    /*
    *   {inherit}
    */
    public function firstDayOfYear(): DateInterface
    {
        $result = $this->datetime->modify(
            'first day of january midnight'
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   {inherit}
    */
    public function firstDayOfHalf(): DateInterface
    {
        $month_string = $this->monthStringDayOfHalf();
        return $this->createDayOf('first', $month_string);
    }

    /*
    *   {inherit}
    */
    public function firstDayOfQuater(): DateInterface
    {
        $month_string = $this->monthStringDayOfQuater();
        return $this->createDayOf('first', $month_string);
    }

    /*
    *   {inherit}
    */
    public function firstDayOfMonth(): DateInterface
    {
        $result = $this->datetime->modify(
            'first day of midnight'
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   lastDayOfYear
    *
    *   @return DateInterface
    */
    public function lastDayOfYear(): DateInterface
    {
        $result = $this->datetime->modify(
            'last day of december midnight'
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   lastDayOfHalf
    *
    *   @return DateInterface
    */
    public function lastDayOfHalf(): DateInterface
    {
        $month_string = $this->monthStringDayOfHalf();

        $last_month = static::createFromFormat(
            '!Y-F',
            (string)$this->year() . "-{$month_string}",
        )->addMonths(5);
        $last_month_string = $last_month->format('F');

        return $this->createDayOf('last', $last_month_string);
    }

    /*
    *   lastDayOfQuater
    *
    *   @return DateInterface
    */
    public function lastDayOfQuater(): DateInterface
    {
        $month_string = $this->monthStringDayOfQuater();

        $last_month = static::createFromFormat(
            '!Y-F',
            (string)$this->year() . "-{$month_string}",
        )->addMonths(2);
        $last_month_string = $last_month->format('F');

        return $this->createDayOf('last', $last_month_string);
    }

    /*
    *   {inherit}
    */
    public function lastDayOfMonth(): DateInterface
    {
        $result = $this->datetime->modify(
            'last day of midnight'
        );
        return new $this(
            $this->datetimeToString($result),
            $this->timezone(),
            $this->fiscal_start_month,
        );
    }

    /*
    *   {inherit}
    */
    public function same(
        DateInterface $datetime,
    ): bool {
        return $this === $datetime;
    }

    /*
    *   {inherit}
    */
    public function different(
        DateInterface $datetime,
    ): bool {
        return $this !== $datetime;
    }

    /*
    *   {inherit}
    */
    public function eq(
        DateInterface $datetime,
    ): bool {
        return $this == $datetime;
    }

    /*
    *   {inherit}
    */
    public function ne(
        DateInterface $datetime,
    ): bool {
        return $this != $datetime;
    }

    /*
    *   {inherit}
    */
    public function gt(
        DateInterface $datetime,
    ): bool {
        return $this->datetime >
            $datetime->toDateTimeImmutable();
    }

    /*
    *   {inherit}
    */
    public function ge(
        DateInterface $datetime,
    ): bool {
        return $this->datetime >=
            $datetime->toDateTimeImmutable();
    }

    /*
    *   {inherit}
    */
    public function lt(
        DateInterface $datetime,
    ): bool {
        return $this->datetime <
            $datetime->toDateTimeImmutable();
    }

    /*
    *   {inherit}
    */
    public function le(
        DateInterface $datetime,
    ): bool {
        return $this->datetime <=
            $datetime->toDateTimeImmutable();
    }

    /*
    *   {inherit}
    */
    public function between(
        DateInterface $start,
        DateInterface $end,
    ): bool {
        return $end->ge($start) ?
            $this->ge($start) && $this->le($end) :
            $this->ge($end) && $this->le($start);
    }

    /*
    *   {inherit}
    */
    public function contain(
        DateInterface $start,
        DateInterface $end,
    ): bool {
        return $end->ge($start) ?
            $this->gt($start) && $this->lt($end) :
            $this->gt($end) && $this->lt($start);
    }

    /*
    *   {inherit}
    */
    public function overlap(
        DateInterface $start,
        DateInterface $end,
    ): bool {
        return $end->ge($start) ?
            $this->ge($start) && $this->lt($end) :
            $this->ge($end) && $this->lt($start);
    }

    /*
    *   {inherit}
    */
    public function sameYear(
        DateInterface $datetime,
    ): bool {
        return $this->year() ===
            $datetime->year();
    }

    /*
    *   {inherit}
    */
    public function sameHalf(
        DateInterface $datetime,
    ): bool {
        $this_half = static::toHalf(
            $this,
            $this->fiscal_start_month,
        );

        $target_half = static::toHalf(
            $datetime,
            $datetime->fiscalStartMonth(),
        );

        return $this_half->sameDay($target_half);
    }

    /*
    *   {inherit}
    */
    public function sameQuater(
        DateInterface $datetime,
    ): bool {
        $this_quater = static::toQuater(
            $this,
            $this->fiscal_start_month,
        );

        $target_quater = static::toQuater(
            $datetime,
            $datetime->fiscalStartMonth(),
        );

        return $this_quater->sameDay($target_quater);
    }

    /*
    *   {inherit}
    */
    public function sameMonth(
        DateInterface $datetime,
    ): bool {
        return $this->format('Y-m') ===
            $datetime->format('Y-m');
    }

    /*
    *   {inherit}
    */
    public function sameDay(
        DateInterface $datetime,
    ): bool {
        return $this->format('Y-m-d') ===
            $datetime->format('Y-m-d');
    }

    /*
    *   {inherit}
    */
    public function toArray(): array
    {
        return getdate(
            $this->unixtime()
        );
    }

    /*
    *   {inherit}
    */
    public function year(): int
    {
        return (int)$this->datetime->format('Y');
    }

    /*
    *   half
    *
    *   @return int 0|1
    */
    public function half(): int
    {
        $half = static::toHalf(
            $this,
            $this->fiscal_start_month,
        );

        $month = $half->month();

        return $month === $this->fiscal_start_month ?
            0 : 1;
    }

    /*
    *   quater
    *
    *   @return int 0|1|2|3
    */
    public function quater(): int
    {
        $quater = static::toQuater(
            $this,
            $this->fiscal_start_month,
        );

        $quater_start = static::createFromFormat(
            '!Yn',
            (string)$this->year() .
                $this->fiscal_start_month,
        );

        $except = $quater_start->except(
            $quater,
            false,
        );

        $month = $except->month();

        if ($month < 0) {
            $month += 12;
        }

        if ($month >= 0 && $month < 3) {
            return  0;
        }

        if ($month >= 3 && $month < 6) {
            return  1;
        }

        if ($month >= 6 && $month < 9) {
            return  2;
        }
        return 3;
    }

    /*
    *   {inherit}
    */
    public function month(): int
    {
        return (int)$this->datetime->format('m');
    }

    /*
    *   {inherit}
    */
    public function week(): int
    {
        return (int)$this->datetime->format('w');
    }

    /*
    *   {inherit}
    */
    public function day(): int
    {
        return (int)$this->datetime->format('d');
    }

    /*
    *   {inherit}
    */
    public function hour(): int
    {
        return (int)$this->datetime->format('H');
    }

    /*
    *   {inherit}
    */
    public function minute(): int
    {
        return (int)$this->datetime->format('i');
    }

    /*
    *   {inherit}
    */
    public function second(): int
    {
        return (int)$this->datetime->format('s');
    }

    /*
    *   {inherit}
    */
    public function microsecond(): int
    {
        return (int)$this->datetime->format('u');
    }

    /*
    *   {inherit}
    */
    public function timezone(): DateTimeZoneInterface
    {
        $timezone = $this->datetime->getTimezone();
        return new DateTimeZoneObject(
            $timezone->getName()
        );
    }

    /*
    *   {inherit}
    */
    public function unixtime(): int
    {
        return (int)$this->getTimestamp();
    }

    /*
    *   toDateTime
    *
    *   @return DateTime
    */
    public function toDateTime(): DateTime
    {
        return DateTime::createFromImmutable(
            $this->datetime
        );
    }

    /*
    *   toDateTimeImmutable
    *
    *   @return DateTime
    */
    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return $this->datetime;
    }

    /*
    *   fiscalStartMonth
    *
    *   @return int
    */
    public function fiscalStartMonth(): int
    {
        return $this->fiscal_start_month;
    }

    /*
    *   {inherit}
    */
    public function except(
        DateInterface $targetObject,
        bool $absolute = false,
    ): DateIntervalInterface {
        $result = $this->datetime->diff(
            $targetObject->toDateTimeImmutable(),
            $absolute,
        );

        return DateIntervalObject::createFromDateInterval(
            $result,
        );
    }

    /**
    *   Hereinafter \DateTimeInterface
    */

    /*
    *   {inherit}
    */
    public function diff(
        DateTimeInterface $targetObject,
        bool $absolute = false,
    ): DateInterval {
        return $this->datetime->diff(
            $targetObject,
            $absolute,
        );
    }

    /*
    *   {inherit}
    */
    public function format(
        string $format,
    ): string {
        return $this->datetime->format(
            $format,
        );
    }

    /*
    *   {inherit}
    */
    public function getOffset(): int
    {
        return $this->datetime->getOffset();
    }

    /*
    *   {inherit}
    */
    public function getTimestamp(): int|false
    {
        return $this->datetime->getTimestamp();
    }

    /*
    *   {inherit}
    */
    public function getTimezone(): DateTimeZone
    {
        return $this->datetime->getTimezone();
    }
}
