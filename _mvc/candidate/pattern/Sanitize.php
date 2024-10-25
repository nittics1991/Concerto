<?php

/**
*   Sanitize
*
*   @version 210608
*/

declare(strict_types=1);

namespace candidate\pattern;

final class Sanitize
{
    /**
    *   金額(+/- カンマ区切り 小数許可) => 数値
    *
    *   @param string $val 金額
    *   @return mixed 数値
    */
    public static function moneyToNumber($val)
    {
        return filter_var(
            $val,
            FILTER_SANITIZE_NUMBER_FLOAT,
            ['flags' => FILTER_FLAG_ALLOW_FRACTION]
        );
    }

    /**
    *   Outlook Mail文字列フィルタ
    *
    *   @param string $val 文字列
    *   @return string
    *   @example $val=" 東芝 tatou <tato. toshiba@toshiba.co.jp> ; hanako.toshiba@toshiba.jp"
    *       =>"tato.toshiba@toshiba.co.jp;hanako.toshiba@toshiba.jp";
    *       空白・不要文字削除
    */
    public static function outlookToEmail($val)
    {
        $result = '';
        $ar = mb_split(';', (string)mb_ereg_replace('[\s　]', '', $val));
        if ($ar === false || count($ar) <= 0) {
            return '';
        }

        $sanitized = [];
        foreach ((array)$ar as $adr) {
            if ($adr != '') {
                if (!filter_var($adr, FILTER_VALIDATE_EMAIL)) {
                    mb_ereg('<.+>', $adr, $regs);

                    if (count($regs) == 1) {
                        $after = (string)filter_var(
                            $regs[0],
                            FILTER_SANITIZE_EMAIL
                        );

                        if (mb_strlen($after) > 0) {
                            $sanitized[] = $after;
                        }
                    }
                } else {
                    $sanitized[] = $adr;
                }
            }
        }
        $unique = array_unique($sanitized);
        $result = implode(';', $unique);
        return $result;
    }
}
