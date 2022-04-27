<?php

/**
*   年度データ
*
*   @version 210901
*/

declare(strict_types=1);

namespace Concerto;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;

class FiscalYear
{
    /**
    *   年度コード
    *
    *   @var string[]
    */
    protected const HALF_CODES = ['K', 'S'];

    /**
    *   四半期コード
    *
    *   @var string[]
    */
    protected const QUATER_CODES = ['A', 'B', 'C', 'D'];

    /**
    *   年度開始月
    *
    *   @var int
    */
    protected const HALF_START_MONTH = 4;

    /**
    *   format書式年度定義
    *
    *   @var string
    */
    protected const HALF_FROMAT = 'Q';

    /**
    *   format書式四半期定義
    *
    *   @var string
    */
    protected const QUATER_FROMAT = 'q';

    /**
    *   上期format
    *
    *   @var string
    *   @caution HALF_FROMAT|QUATER_FROMAT
    */
    protected const FIRST_HALF_FROMAT = 'Y年上期';

    /**
    *   下期format
    *
    *   @var string
    *   @caution HALF_FROMAT|QUATER_FROMAT
    */
    protected const LAST_HALF_FROMAT = 'Y年下期';

    /**
    *   上半期年度コード判定
    *
    *   @param mixed $kb_nendo
    *   @return bool
    */
    public static function isFirstHalfCode(
        mixed $kb_nendo,
    ): bool {
        $pattern = '^[0-9]{4}' .
            static::HALF_CODES[0] .
            '$';

        return
            is_string($kb_nendo) &&
            mb_ereg_match(
                $pattern,
                $kb_nendo,
            );
    }

    /**
    *   下半期年度コード判定
    *
    *   @param mixed $kb_nendo
    *   @return bool
    */
    public static function isLastHalfCode(
        mixed $kb_nendo,
    ): bool {
        $pattern = '^[0-9]{4}' .
            static::HALF_CODES[1] .
            '$';

        return
            is_string($kb_nendo) &&
            mb_ereg_match(
                $pattern,
                $kb_nendo,
            );
    }

    /**
    *   年度コード判定
    *
    *   @param mixed $kb_nendo
    *   @return bool
    */
    public static function isHalfCode(
        mixed $kb_nendo,
    ): bool {
        return static::isFirstHalfCode($kb_nendo) ||
            static::isLastHalfCode($kb_nendo);
    }

    /**
    *   上半期年度内判定
    *
    *   @param ?DateTimeInterface $datetime
    *   @return bool
    */
    public static function inFirstHalf(
        ?DateTimeInterface $datetime = null,
    ): bool {
        $datetime = $datetime ?? new DateTimeImmutable();

        $half_start = (new DateTimeImmutable())
            ->setDate(
                (int)$datetime->format('Y'),
                (int)static::HALF_START_MONTH,
                1,
            )->modify('first day of today');

        $period = new DatePeriod(
            $half_start,
            new DateInterval('P1M'),
            5,
        );

        $month = $datetime->format('m');

        foreach ($period as $date) {
            if ($date->format('m') === $month) {
                return true;
            }
        }
        return false;
    }

    /**
    *   下半期年度内判定
    *
    *   @param ?DateTimeInterface $datetime
    *   @return bool
    */
    public static function inLastHalf(
        ?DateTimeInterface $datetime = null,
    ): bool {
        return !static::inFirstHalf($datetime);
    }

    /**
    *   年度コード分割
    *
    *   @param mixed $kb_nendo
    *   @return string[] ['year', 'code']
    */
    public static function parseCode(
        mixed $kb_nendo,
    ): array {
        if (!static::isHalfCode($kb_nendo)) {
            return [];
        }

        return [
            'year' => mb_substr($kb_nendo, 0, 4),
            'code' => mb_substr($kb_nendo, 4, 1),
        ];
    }

    /**
    *   DateTimeInterface => 年度コード
    *
    *   @param ?DateTimeInterface $datetime
    *   @return string
    */
    public static function datetimeToCode(
        ?DateTimeInterface $datetime = null,
    ): string {
        $this_month = isset($datetime) ?
            (DateTimeImmutable::createFromInterface(
                $datetime,
            ))->modify('first day of today') :
            new DateTimeImmutable('first day of today');

        $first_half_month = $this_month->setDate(
            (int)$this_month->format('Y'),
            (int)static::HALF_START_MONTH,
            1,
        )->modify('first day of today');

        $last_half_month =
            $first_half_month->add(
                new DateInterval("P6M")
            );

        if ($this_month >= $last_half_month) {
            return $first_half_month->format('Y') .
                static::HALF_CODES[1];
        }

        if ($this_month >= $first_half_month) {
            return $first_half_month->format('Y') .
                static::HALF_CODES[0];
        }

        $previous_half_month =
            $first_half_month->sub(
                new DateInterval("P6M")
            );

        return $this_month >= $previous_half_month ?
            $this_month->sub(
                new DateInterval("P1Y")
            )->format('Y') . static::HALF_CODES[1] :
            $this_month->sub(
                new DateInterval("P1Y")
            )->format('Y') . static::HALF_CODES[0];
    }

    /**
    *   年度コード => DateTimeImmutable
    *
    *   @param ?string $kb_nendo
    *   @return ?DateTimeImmutable
    */
    public static function codeToDatetime(
        ?string $kb_nendo = null,
    ): ?DateTimeImmutable {
        $kb_nendo = $kb_nendo ?? static::getPresentNendo();

        if (!static::isHalfCode($kb_nendo)) {
            return null;
        }

        $parsed_code = static::parseCode($kb_nendo);
        $half_start = DateTimeImmutable::createFromFormat(
            '!Yn',
            $parsed_code['year'] . (string)static::HALF_START_MONTH
        );

        if (!$half_start) {
            return null;
        }

        $half_start = $half_start->modify('first day of today');

        if (!$half_start) {
            return null;
        }

        $half_start =  $parsed_code['code'] === static::HALF_CODES[0] ?
            $half_start :
            $half_start->add(
                new DateInterval("P6M")
            );

        return !$half_start ? null : $half_start;
    }

    /**
    *   Datetimeで指定したDatePeriod
    *
    *   @param ?DateTimeInterface $datetime
    *   @return DatePeriod
    */
    public static function datetimeInPeriod(
        ?DateTimeInterface $datetime = null,
    ): DatePeriod {
        $datetime = $datetime ??
            new DateTimeImmutable('first day of today');

        $fitst_half_start = (new DateTimeImmutable())
            ->setDate(
                (int)$datetime->format('Y'),
                (int)static::HALF_START_MONTH,
                1,
            )->modify('first day of today');

        $last_half_start = $fitst_half_start->add(
            new DateInterval("P6M")
        );
        $previous_half_start = $fitst_half_start->sub(
            new DateInterval("P6M")
        );

        if ($datetime >= $last_half_start) {
            $half_start = $last_half_start;
        } elseif ($datetime >= $fitst_half_start) {
            $half_start = $fitst_half_start;
        } elseif ($datetime >= $previous_half_start) {
            $half_start = $previous_half_start;
        } else {
            $half_start = $fitst_half_start->sub(
                new DateInterval("P1Y")
            );
        }

        return new DatePeriod(
            $half_start,
            new DateInterval('P1M'),
            5,
        );
    }

    /**
    *   コードで指定したDatePeriod
    *
    *   @param ?string $kb_nendo
    *   @return ?DatePeriod
    */
    public static function codeInPeriod(
        ?string $kb_nendo = null,
    ): ?DatePeriod {
        $kb_nendo = $kb_nendo ?? static::getPresentNendo();

        if (!static::isHalfCode($kb_nendo)) {
            return null;
        }

        $half_start = static::codeToDatetime($kb_nendo);

        if (!$half_start) {
            return null;
        }
        return static::datetimeInPeriod($half_start);
    }

    /**
    *   書式化した期間
    *
    *   @param ?string $kb_nendo
    *   @return string[]
    */
    public static function formattedPeriod(
        string $format,
        ?string $kb_nendo = null,
    ): array {
        $period = static::codeInPeriod($kb_nendo);

        if (!$period) {
            return [];
        }

        $year_months = [];

        foreach ($period as $date) {
            $year_months[] = $date->format($format);
        }
        return $year_months;
    }

    /**
    *   現在年度
    *
    *   @return string
    */
    public static function getPresentNendo(): string
    {
        return static::datetimeToCode();
    }

    /**
    *   指定年度のn期後
    *
    *   @param string $kb_nendo
    *   @param int $diff
    *   @return string|false
    */
    public static function addNendo(
        string $kb_nendo,
        int $diff
    ): string | false {
        if (!static::isHalfCode($kb_nendo)) {
            return false;
        }

        $half_start = static::codeToDatetime($kb_nendo);

        if (!$half_start) {
            return false;
        }

        $month = $diff * 6;

        if ($month >= 0) {
            $new_month = $half_start->add(
                new DateInterval("P{$month}M")
            );
        } else {
            $month = (string)abs($month);
            $new_month = $half_start->sub(
                new DateInterval("P{$month}M")
            );
        }

        if (!$new_month) {
            return false;
        }

        return static::datetimeToCode($new_month);
    }

    /**
    *   指定年度の次年度
    *
    *   @param ?string $kb_nendo
    *   @return string|false
    */
    public static function getNextNendo(
        ?string $kb_nendo = null,
    ): string | false {
        $kb_nendo = $kb_nendo ?? static::getPresentNendo();
        return static::addNendo($kb_nendo, 1);
    }

    /**
    *   指定年度の前年度
    *
    *   @param ?string $kb_nendo
    *   @return string|false
    */
    public static function getPreviousNendo(
        ?string $kb_nendo = null,
    ): string | false {
        $kb_nendo = $kb_nendo ?? static::getPresentNendo();
        return static::addNendo($kb_nendo, -1);
    }

    /**
    *   年度コード＝＞年度format
    *
    *   @param string $kb_nendo
    *   @param string $convert_option @caution mb_convert_kana $option
    *   @return string|false
    */
    public static function nendoCodeToZn(
        string $kb_nendo,
        string $convert_option = 'KVA',
    ): string | false {
        if (static::isFirstHalfCode($kb_nendo)) {
            $format = static::FIRST_HALF_FROMAT;
        } elseif (static::isLastHalfCode($kb_nendo)) {
            $format = static::LAST_HALF_FROMAT;
        } else {
            return false;
        }

        $parsed_code = static::parseCode($kb_nendo);
        $format_chars = mb_str_split(
            $format,
            1,
        );

        $date_formats = [];
        $escape_char = false;

        foreach ($format_chars as $char) {
            //前文字がエスケープ
            if ($escape_char) {
                $date_formats[] = $char;
                $escape_char = false;
            } elseif ($char === '\\') {
                $escape_char = true;
            } elseif ($char === static::HALF_FROMAT) {
                $date_formats[] = $parsed_code['code'];
            } elseif ($char === 'Y') {
                $date_formats[] = $parsed_code['year'];
            } elseif ($char === 'y') {
                $date_formats[] = $parsed_code['year'];
            } else {
                $date_formats[] = $char;
            }
        }

        return mb_convert_kana(
            implode('', $date_formats),
            $convert_option,
        );
    }

    /**
    *   年度内年月
    *
    *   @param ?string $kb_nendo
    *   @return string[] [yyyymm,...]
    */
    public static function getNendoyyyymm(
        ?string $kb_nendo = null,
    ): array {
        return static::formattedPeriod(
            'Ym',
            $kb_nendo,
        );
    }

    /**
    *   年度内月
    *
    *   @param ?string $kb_nendo
    *   @return string[] [yyyymm,...]
    */
    public static function getNendomm(
        ?string $kb_nendo = null,
    ): array {
        return static::formattedPeriod(
            'm',
            $kb_nendo,
        );
    }

    /**
    *   年月=>年度コード
    *
    *   @param ?string $yyyymm
    *   @return string|false
    */
    public static function getyyyymmToNendo(
        ?string $yyyymm = null,
    ): string | false {
        $yyyymm = $yyyymm ?? date('Ym');

        if (!mb_ereg_match('^\d{6}$', $yyyymm)) {
            return false;
        }

        $month = (int)mb_substr($yyyymm, 4);

        if ($month < 1 || $month > 12) {
            return false;
        }

        $datetime = DateTimeImmutable::createFromFormat(
            '!Ym',
            $yyyymm
        );

        if (!$datetime) {
            return false;
        }

        $datetime = $datetime->modify('first day of today');

        return static::datetimeToCode($datetime);
    }

    /**
    *   年度コード=>開始年月・終了年月
    *
    *   @param string $kb_nendo 年度(yyyyK or yyyyS)
    *   @return string[] [yyyymm, yyyymm]
    */
    public static function getNendoPeriod(
        string $kb_nendo,
    ): array {
        if (!static::isHalfCode($kb_nendo)) {
            return [];
        }
        $yyyymm = static::getNendoyyyymm($kb_nendo);
        return [$yyyymm[0], $yyyymm[5]];
    }

    /**
    *   指定期間の年度のコレクション
    *
    *   @param string $kb_nendo_s
    *   @param string $kb_nendo_e
    *   @return array[] [['kb_nendo' => '', 'nm_nendo' => ''], ...]
    */
    public static function getNendoPeriodCollection(
        string $kb_nendo_s,
        string $kb_nendo_e,
    ): array {
        if (!static::isHalfCode($kb_nendo_s)) {
            return [];
        }

        if (!static::isHalfCode($kb_nendo_e)) {
            return [];
        }

        if ($kb_nendo_e >= $kb_nendo_s) {
            $current = $kb_nendo_s;
            $end = $kb_nendo_e;
            $reverse = false;
        } else {
            $current = $kb_nendo_e;
            $end = $kb_nendo_s;
            $reverse = true;
        }

        do {
            $items['kb_nendo'] = $current;
            $items['nm_nendo'] = static::nendoCodeToZn($current);
            $result[] = $items;
            $current = (string)static::getNextNendo($current);
        } while ($current <= $end);

        if ($reverse) {
            krsort($result);
        }
        return $result;
    }

    /**
    *   会計年度の期差
    *
    *   @param string $baseNendo
    *   @param string $targetNendo
    *   @return int
    */
    public static function diff(
        string $baseNendo,
        string $targetNendo,
    ): int {
        if ($baseNendo === $targetNendo) {
            return 0;
        }

        $baseYear = (int)mb_substr($baseNendo, 0, 4);
        $baseKi = mb_substr($baseNendo, 4, 1);

        $targetYear = (int)mb_substr($targetNendo, 0, 4);
        $targetKi = mb_substr($targetNendo, 4, 1);

        if ($baseKi === $targetKi) {
            return ($targetYear - $baseYear) * 2
            ;
        }

        $diff = ($targetYear - $baseYear) * 2;
        return $baseKi === 'K' ? $diff + 1 : $diff - 1;
    }
}
