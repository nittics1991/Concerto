<?php

/**
*   String Util
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\standard;

use InvalidArgumentException;

final class StringUtil
{
    /**
    *   JSON escape encode
    *
    *   @param mixed $data
    *   @return string JSON
    */
    public static function jsonEncode($data): string
    {
        $json = json_encode(
            $data,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
        );

        if ($json === false) {
            throw new InvalidArgumentException(
                "json encode error"
            );
        }
        return $json;
    }

    /**
    *   JSON整形
    *
    *   @param string $json JSON
    *   @return string 整形後JSON
    */
    public static function jsonFormating(string $json): string
    {
        $formated = json_encode(
            json_decode($json),
            JSON_PRETTY_PRINT
        );

        if ($formated === false) {
            throw new InvalidArgumentException(
                "json format error"
            );
        }
        return $formated;
    }

    /**
    *   文字を1文字毎配列変換
    *
    *   @param string $string
    *   @return string[]
    */
    public static function strToArray($string): array
    {
        $splited = preg_split("//u", $string, -1, PREG_SPLIT_NO_EMPTY);

        if ($splited === false) {
            throw new InvalidArgumentException(
                "split error"
            );
        }
        return $splited;
    }

    /**
    *   javascriptリテラルエスケープ
    *
    *   @param string $str
    *   @return string
    *   @example escapeJavascriptString('abcd012') => 'abcd012'
    *       ASCIIの内英数字はそのまま その他はエスケープ 2byte以上はそのまま
    */
    public static function escapeJavascript(string $str): string
    {
        $map = [
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,0,0, // 49
                0,0,0,0,0,0,0,0,1,1,
                1,1,1,1,1,0,0,0,0,0,
                0,0,0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,0,0,
                0,1,1,1,1,1,1,0,0,0, // 99
                0,0,0,0,0,0,0,0,0,0,
                0,0,0,0,0,0,0,0,0,0,
                0,0,0,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1, // 149
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1, // 199
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1,
                1,1,1,1,1,1,1,1,1,1, // 249
                1,1,1,1,1,1,1, // 255
        ];

        // 文字エンコーディングはUTF-8
        $mblen = mb_strlen($str, 'UTF-8');
        $utf32 = mb_convert_encoding($str, 'UTF-32', 'UTF-8');
        $convmap = [0x0, 0xffffff, 0, 0xffffff ];
        for ($i = 0, $encoded = ''; $i < $mblen; $i++) {
            // Unicodeの仕様上、最初のバイトは無視してもOK
            $chr =  (ord($utf32[$i * 4 + 1]) << 16)
                + (ord($utf32[$i * 4 + 2]) << 8) + ord($utf32[$i * 4 + 3]);
            if ($chr < 256 && $map[$chr]) {
                if ($chr < 10) {
                    $encoded .= '\\x0' . base_convert((string)$chr, 10, 16);
                } else {
                    $encoded .= '\\x' . base_convert((string)$chr, 10, 16);
                }
            } elseif ($chr == 2028) {
                $encoded .= '\\u2028';
            } elseif ($chr == 2029) {
                $encoded .= '\\u2029';
            } else {
                $encoded .= mb_decode_numericentity(
                    '&#' . $chr . ';',
                    $convmap,
                    'UTF-8'
                );
            }
        }
        return $encoded;
    }

    /**
    *   文字列＝＞16進コード
    *
    *   @param string $string
    *   @return string[]
    */
    public static function strToCode(string $string): array
    {
        $ar = static::strToArray($string);

        return array_map(
            function ($val) {
                return bin2hex($val);
            },
            $ar
        );
    }

    /**
    *   16進コード＝＞文字列
    *
    *   @param string[] $array
    *   @return string
    */
    public static function codeToStr(array $array): string
    {
        $bins = array_map(
            function ($val) {
                return hex2bin($val);
            },
            $array
        );
        return implode('', $bins);
    }

    /**
    *   token分解
    *
    *   @param string $string
    *   @return string[]
    */
    public static function token(string $string): array
    {
        $splited = (array)mb_split('\s', $string);

        return array_values(
            array_filter(
                $splited,
                function ($val) {
                    return $val != '';
                }
            )
        );
    }
}
