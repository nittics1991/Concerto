<?php

/**
*   DateHelper
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\date;

use DateTimeImmutable;
use DateTimeInterface;

class DateHelper
{
    /**
    *   toJulianDay
    *
    *   @param DateTimeInterface $datatime
    *   @return float
    */
    public static function toJulianDay(
        DateTimeInterface $datatime,
    ): float {
        $year = (int)$datatime->format('Y');
        $month = (int)$datatime->format('m');
        $day = (int)$datatime->format('d');
        $hour = (int)$datatime->format('H');
        $minute = (int)$datatime->format('i');
        $second = (int)$datatime->format('s');

        if ($month <= 2) {
            $year -= 1;
            $month += 12;
        }

        $a = floor($year / 100);

        $b = 2 - $a + floor($a / 4);

        $jd = floor(365.25 * ($year + 4716)) +
            floor(30.6001 * ($month + 1)) +
            $day +
            $b -
            1524.5;

        $fractionalDay = (
            $hour + $minute / 60 + $second / 3600
        ) / 24;

        return $jd + $fractionalDay;
    }

    /**
    *   toModifiedJulianDay
    *
    *   @param DateTimeInterface $datatime
    *   @return float
    */
    public static function toModifiedJulianDay(
        DateTimeInterface $datatime,
    ): float {
        return self::toJulianDay($datatime) - 2400000.5;
    }

    /**
    *   fromJulianDay
    *
    *   @param float $jd
    *   @return DateTimeImmutable
    */
    public static function fromJulianDay(
        float $jd,
    ): DateTimeImmutable {
        $jd += 0.5;
        $z = (int)$jd;
        $f = $jd - $z;

        if ($z < 2299161) {
            $a = $z;
        } else {
            $alpha = (int)(($z - 1867216.25) / 36524.25);
            $a = $z + 1 + $alpha - (int)($alpha / 4);
        }

        $b = $a + 1524;
        $c = (int)(($b - 122.1) / 365.25);
        $d = (int)(365.25 * $c);
        $e = (int)(($b - $d) / 30.6001);
        $day = floor($b - $d - (int)(30.6001 * $e) + $f);

        if ($e < 14) {
            $month = $e - 1;
        } else {
            $month = $e - 13;
        }

        if ($month > 2) {
            $year = $c - 4716;
        } else {
            $year = $c - 4715;
        }

        $hour = (int)($f * 24);
        $minute = (int)(($f * 24 - $hour) * 60);
        $second = (int)(($f * 24 - $hour - $minute / 60) * 3600);

        return new DateTimeImmutable(
            "{$year}-{$month}-{$day} {$hour}:{$minute}:{$second}"
        );
    }

    /**
    *   fromModifiedJulianDay
    *
    *   @param float $mjd
    *   @return DateTimeImmutable
    */
    public static function fromModifiedJulianDay(
        float $mjd,
    ): DateTimeImmutable {
        return self::fromJulianDay($mjd + 2400000.5);
    }
}
