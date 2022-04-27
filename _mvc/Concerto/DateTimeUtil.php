<?php

/**
*   日付データ
*
*   @version 210901
*/

declare(strict_types=1);

namespace Concerto;

use DateTime;
use DateInterval;
use DatePeriod;
use InvalidArgumentException;
use RuntimeException;

final class DateTimeUtil
{
    /**
    *   指定期間日付最大数
    *
    *   @var int
    */
    public const PERIOD_MAX = 1000;

    /**
    *   指定期間の日付
    *
    *   @param string $start 開始年月日
    *   @param string $end 終了年月日
    *   @param string $interval 日付間隔
    *   @param string $format 書式
    *   @param int $limit 最大回数
    *   @return string[]
    */
    public static function periodDate(
        string $start,
        string $end,
        string $interval = 'P1D',
        string $format = 'Ymd',
        int $limit = 100
    ): array {
        $s = new DateTime($start);
        $e = new DateTime($end);

        if ($s > $e) {
            throw new InvalidArgumentException(
                "greater than end of the start"
            );
        }

        if ($s == $e) {
            return [$s->format($format)];
        }

        if (!is_int($limit) || ($limit > DateTimeUtil::PERIOD_MAX)) {
            throw new InvalidArgumentException(
                "limit is less " . DateTimeUtil::PERIOD_MAX
            );
        }

        $dateInterval = new DateInterval($interval);
        $datePeriod = new DatePeriod($s, $dateInterval, $e);

        $items = [];
        $count = 0;
        $dateTime = null;

        foreach ($datePeriod as $dateTime) {
            $items[] = $dateTime->format($format);

            $count++;
            if ($count > $limit) {
                throw new RuntimeException(
                    "it exceeded the number of processing times"
                );
            }
        }

        $decision = (is_null($dateTime)) ? clone $e : clone $dateTime;

        if ($decision->add($dateInterval) == $e) {
            $items[] = $e->format($format);
        }

        return $items;
    }

    /**
    *   指定期間の年月日
    *
    *   @param string $start 開始年月日
    *   @param string $end 終了年月日
    *   @return string[]
    */
    public static function getIntervalYYYYMMDD(
        string $start,
        string $end
    ): array {
        return static::periodDate($start, $end);
    }

    /**
    *   指定期間の年月
    *
    *   @param string $start 開始年月日
    *   @param string $end 終了年月日
    *   @return string[]
    */
    public static function getIntervalYYYYMM(
        string $start,
        string $end
    ): array {
        return static::periodDate($start, $end, 'P1M', 'Ym');
    }

    /**
    *   第n曜日=>日付
    *
    *   @param int $year 年
    *   @param int $month 月
    *   @param int $no (1, 2, ・・・)
    *   @param int $week 曜日(0-6)
    *   @return DateTime
    */
    public static function getNoWeekToDate(
        int $year,
        int $month,
        int $no,
        int $week
    ): DateTime {

        /*
        if (!is_int($year) ||
            !is_int($year) ||
            !is_int($year) ||
            !is_int($year)
        ) {
            throw new InvalidArgumentException("int is required");
        }
        */

        if ($week < 0 || $week > 6) {
            throw new InvalidArgumentException("$week(0-6)");
        }

        $date = new DateTime();
        $date = $date->setTime(0, 0, 0);
        $first_week = $date->setDate($year, $month, 1)->format('w');

        $day = ($no - 1) * 7 + 1;
        $diff = $week - (int)$first_week;

        $day += $diff;

        if ($diff < 0) {
            $day += 7;
        }
        return $date->setDate($year, $month, $day);
    }

    /**
    *   YYYYMMDD => YYYY/MM/DD
    *
    *   @param string  $val
    *   @return string
    */
    public static function YYYYMMDDaddSlash(string $val): string
    {
        if (mb_strlen($val) != 8) {
            return $val;
        }
        return mb_substr($val, 0, 4) . '/' .
            mb_substr($val, 4, 2) . '/' .
            mb_substr($val, 6, 2);
    }

    /**
    *   YYYYMMDD => YYYY-MM-DD
    *
    *   @param string $val
    *   @return string
    */
    public static function YYYYMMDDaddHyphen(string $val): string
    {
        if (mb_strlen($val) != 8) {
            return $val;
        }
        return mb_substr($val, 0, 4) . '-' .
            mb_substr($val, 4, 2) . '-' .
            mb_substr($val, 6, 2);
    }

    /**
    *   指定月数移動した年月
    *
    *   @param string $yyyymm
    *   @param int $interval_day
    *   @return string
    */
    public static function modifyYYYYMM(
        string $yyyymm,
        int $interval_day,
    ): string {
        $dt = DateTime::createFromFormat('!Ym', $yyyymm);

        if ($dt === false) {
            throw new InvalidArgumentException(
                "fairure create date"
            );
        }

        if ($interval_day >= 0) {
            return $dt->add(
                new DateInterval("P{$interval_day}M")
            )->format('Ym');
        }

        $interval_day = abs($interval_day);
        return $dt->sub(
            new DateInterval("P{$interval_day}M")
        )->format('Ym');
    }
}
