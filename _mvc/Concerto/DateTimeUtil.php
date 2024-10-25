<?php

/**
*   日付データ
*
*   @version 240826
*/

declare(strict_types=1);

namespace Concerto;

use DateTimeImmutable;
use DateInterval;
use DatePeriod;
use InvalidArgumentException;

final class DateTimeUtil
{
    /**
    *   指定期間の年月
    *
    *   @param string $start Ymd
    *   @param string $end Ymd
    *   @return string[] [Ym,...]
    */
    public static function getIntervalYYYYMM(
        string $start,
        string $end
    ): array {
        $dt_start = DateTimeImmutable::createFromFormat(
            '!Ymd',
            $start > $end ? $end : $start,
        );

        if ($dt_start === false) {
            throw new InvalidArgumentException(
                "start date error:{$start}",
            );
        }

        $dt_end = DateTimeImmutable::createFromFormat(
            '!Ymd',
            $start > $end ? $start : $end,
        );

        if ($dt_end === false) {
            throw new InvalidArgumentException(
                "end date error:{$end}",
            );
        }

        $dt_start_m = $dt_start->modify(
            'first day of this month',
        );

        /** @var DateTimeImmutable|false $dt_start_m */
        if ($dt_start_m === false) {
            throw new InvalidArgumentException(
                "start date modify error:{$start}",
            );
        }

        $dt_end_m = $dt_end->modify(
            'first day of this month',
        );

        /** @var DateTimeImmutable|false $dt_end_m */
        if ($dt_end_m === false) {
            throw new InvalidArgumentException(
                "end date modify error:{$end}",
            );
        }

        $period = new DatePeriod(
            $dt_start_m,
            new DateInterval('P1M'),
            $dt_end_m,
            DatePeriod::INCLUDE_END_DATE,
        );

        $result = [];

        foreach ($period as $date) {
            $result[] = $date->format('Ym');
        }

        return $result;
    }
}
