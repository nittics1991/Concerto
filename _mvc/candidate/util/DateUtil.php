<?php

/**
*   DateUtil
*
*   @version 230215
*/

declare(strict_types=1);

namespace candidate\util;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use RuntimeException;
use Concerto\FiscalYear;

final class DateUtil
{
    /**
    *   @var DateTimeImmutable
    */
    private DateTimeImmutable $date;

    /**
    *   __construct
    *
    *   @param DateTimeInterface $date
    */
    public function __construct(
        DateTimeInterface $date,
    ) {
        $this->date = $date instanceof DateTimeImmutable ?
            $date :
            DateTimeImmutable::createFromMutable(
                $date,
            );
    }

    /**
    *   now
    *
    *   @return self
    */
    public static function now(): self
    {
        return new self(
            new DateTimeImmutable(
                'now',
            ),
        );
    }

    /**
    *   today
    *
    *   @return self
    */
    public static function today(): self
    {
        return new self(
            new DateTimeImmutable(
                'today',
            ),
        );
    }

    /**
    *   month
    *
    *   @return self
    */
    public static function month(): self
    {
        return new self(
            new DateTimeImmutable(
                'first day of this month today',
            ),
        );
    }

    /**
    *   half
    *
    *   @return self
    */
    public static function half(): self
    {
        return self::fromHalf(
            FiscalYear::getPresentNendo(),
        );
    }

    /**
    *   fromHalf
    *
    *   @param string $half yyyyK|yyyyS
    *   @return self
    */
    public static function fromHalf(
        string $half,
    ): self {
        $date = FiscalYear::codeToDateTime(
            $half,
        );

        if (!$date) {
            throw new InvalidArgumentException(
                "invalid half string:{$half}",
            );
        }

        return new self($date);
    }

    /**
    *   fromMonth
    *
    *   @param string $yyyymm
    *   @return self
    */
    public static function fromMonth(
        string $yyyymm,
    ): self {
        $date = DateTimeImmutable::createFromFormat(
            '!Ym',
            $yyyymm,
        );

        if (!$date) {
            throw new InvalidArgumentException(
                "invalid half string:{$yyyymm}",
            );
        }

        return new self($date);
    }

    /**
    *   fromDate
    *
    *   @param string $yyyymmdd
    *   @return self
    */
    public static function fromDate(
        string $yyyymmdd,
    ): self {
        $date = DateTimeImmutable::createFromFormat(
            '!Ymd',
            $yyyymmdd,
        );

        if (!$date) {
            throw new InvalidArgumentException(
                "invalid half string:{$yyyymmdd}",
            );
        }

        return new self($date);
    }

    /**
    *   toDateTimeImmutable
    *
    *   @return DateTimeImmutable
    */
    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
    *   toHalf
    *
    *   @return string yyyyK|yyyyS
    */
    public function toHalf(): string
    {
        $yyyymm = $this->date->format('Ym');

        return (string)FiscalYear::getyyyymmToNendo($yyyymm);
    }

    /**
    *   toTime
    *
    *   @return string Ymd His
    */
    public function toTime(): string
    {
        return $this->date->format('Ymd His');
    }

    /**
    *   toDate
    *
    *   @return string Ymd
    */
    public function toDate(): string
    {
        return $this->date->format('Ymd');
    }

    /**
    *   toMonth
    *
    *   @return string Ym
    */
    public function toMonth(): string
    {
        return $this->date->format('Ym');
    }

    /**
    *   toFirstHalfMonth
    *
    *   @return string Ym
    */
    public function toFirstHalfMonth(): string
    {
        $half = FiscalYear::getyyyymmToNendo(
            $this->date->format('Ym'),
        );

        if (!$half) {
            throw new RuntimeException(
                "faild to get half",
            );
        }

        $yyyymms = FiscalYear::getNendoyyyymm(
            $half,
        );

        return $yyyymms[0];
    }

    /**
    *   toLastHalfMonth
    *
    *   @return string Ym
    */
    public function toLastHalfMonth(): string
    {
        $half = FiscalYear::getyyyymmToNendo(
            $this->date->format('Ym'),
        );

        if (!$half) {
            throw new RuntimeException(
                "faild to get half",
            );
        }

        $yyyymms = FiscalYear::getNendoyyyymm(
            $half,
        );

        return $yyyymms[5];
    }

    /**
    *   addInterval
    *
    *   @param string $interval use DateInterval syntax
    *   @return self
    */
    public function addInterval(
        string $interval,
    ): self {
        return new self(
            $this->date->add(
                new DateInterval($interval),
            ),
        );
    }

    /**
    *   subInterval
    *
    *   @param string $interval use DateInterval syntax
    *   @return self
    */
    public function subInterval(
        string $interval,
    ): self {
        return new self(
            $this->date->sub(
                new DateInterval($interval),
            ),
        );
    }
}
