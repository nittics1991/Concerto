<?php

/**
*   DateTimeStringParser
*
*   @version 221219
*/

declare(strict_types=1);

namespace Concerto\win;

use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;

class DateTimeStringParser
{
    /**
    *   parse
    *
    *   @param string $dateString
    *   @return DateTimeImmutable
    */
    public static function parse(
        string $dateString
    ): DateTimeImmutable {
        if (
            !mb_ereg_match(
                '\A\d{14}\.*\d*[+-]\d{1,3}\z',
                $dateString
            )
        ) {
            throw new InvalidArgumentException(
                "unmatch datetime format:{$dateString}"
            );
        }

        $date = mb_substr($dateString, 0, 14);

        $plus = mb_strpos($dateString, '+');

        $minus = mb_strpos($dateString, '-');

        $pos = ($plus !== false) ? $plus : $minus;

        $zoneDirection = mb_substr($dateString, (int)$pos, 1);

        $zoneTime = intval(
            mb_substr($dateString, $pos + 1)
        );

        $zoneHour = sprintf('%02d', intval($zoneTime / 60));

        $zoneMinute = sprintf('%02d', ($zoneTime % 60));

        $result = DateTimeImmutable::createFromFormat(
            'YmdHisT',
            "{$date}{$zoneDirection}{$zoneHour}{$zoneMinute}"
        );

        if ($result === false) {
            throw new RuntimeException(
                "parse error:{$dateString}"
            );
        }

        return $result;
    }
}
