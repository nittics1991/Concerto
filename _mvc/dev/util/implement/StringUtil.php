<?php

/**
*   StringUtil
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\util\implement;

use InvalidArgumentException;
use Concerto\util\StringInterface;

class StringUtil implements StringInterface
{
    /*
    *   @var string
    */
    protected const SPACE_PATTERN =
        "[ \t\n\r\0\x0B\x0C\u{A0}\u{FEFF}]";

    /*
    *   lower
    *
    *   @param string $string
    *   @return string
    */
    public static function lower(
        string $string,
    ): string {
        return mb_strtolower($string);
    }

    /*
    *   upper
    *
    *   @param string $string
    *   @return string
    */
    public static function upper(
        string $string,
    ): string {
        return mb_strtoupper($string);
    }

    /*
    *   title
    *
    *   @param string $string
    *   @return string
    */
    public static function title(
        string $string,
    ): string {
        return mb_convert_case(
            $string,
            MB_CASE_TITLE
        );
    }

    /*
    *   snake
    *
    *   @param string $string
    *   @return string
    *   @caution
    *       '_mst_Bumon_data' ==> '_mst__bumon_data' //unsder scoreはそのまま残る
    *       'mstBumon_Data' ==> 'mst_bumon__data' //_Data ==> __dataとなる
    */
    public static function snake(
        string $string,
    ): string {
        $replaced = mb_ereg_replace_callback(
            '[A-Z]',
            function ($matches) {
                return '_'  . mb_strtolower($matches[0]);
            },
            $string
        );

        if ($replaced === null) {
            throw new InvalidArgumentException(
                "encoding error:{$string}"
            );
        }

        if ($replaced === false) {
            throw new InvalidArgumentException(
                "trim error:{$string}"
            );
        }

        if (
            mb_substr($replaced, 0, 1) === '_' &&
            mb_substr($string, 0, 1) !== '_'
        ) {
            return mb_substr($replaced, 1);
        }
        return $replaced;
    }

    /*
    *   camel
    *
    *   @param string $string
    *   @return string
    */
    public static function camel(
        string $string,
    ): string {
        $snaked = static::toSnake($string);
        $replaced = static::replace(
            $snaked,
            '_',
            ' ',
        );

        $titled = static::title($replaced);

        return static::implode(
            '',
            static::explode(
                ' ',
                $titled
            )
        );
    }

    /*
    *   study
    *
    *   @param string $string
    *   @return string
    */
    public static function study(
        string $string,
    ): string {
        $uppered = static::camel($string);
        return mb_strtolower(mb_substr($uppered, 0, 1)) .
            mb_substr($uppered, 1);
    }

    /*
    *   kana
    *
    *   @param string $string
    *   @param string $mode
    *   @return string
    */
    public static function kana(
        string $string,
        string $mode,
    ): string {
        return mb_convert_kana(
            $string,
            $mode,
        );
    }

    /*
    *   length
    *
    *   @param string $string
    *   @return int
    */
    public static function length(
        string $string,
    ): int {
        return mb_strlen($string);
    }

    /*
    *   width
    *
    *   @param string $string
    *   @return int
    */
    public static function width(
        string $string,
    ): int {
        return mb_strwidth($string);
    }

    /*
    *   trim
    *
    *   @param string $string
    *   @return string
    */
    public static function trim(
        string $string,
    ): string {
        $pattern = static::SPACE_PATTERN;
        $replaced = (string)mb_ereg_replace(
            "\A{$pattern}+|{$pattern}+\z",
            '',
            $string
        );

        if ($replaced === null) {
            throw new InvalidArgumentException(
                "encoding error:{$string}"
            );
        }

        if ($replaced === false) {
            throw new InvalidArgumentException(
                "trim error:{$string}"
            );
        }
        return $replaced;
    }

    /*
    *   insert
    *
    *   @param string $string
    *   @param string $insert
    *   @param int $offset
    *   @return string
    */
    public static function insert(
        string $string,
        string $insert,
        int $offset,
    ): string {
        return static::splice(
            $string,
            $offset,
            0,
            $insert,
        );
    }

    /*
    *   delete
    *
    *   @param string $string
    *   @param ?int $offset
    *   @param ?int $length
    *   @return string
    */
    public static function delete(
        string $string,
        int $offset,
        int $length,
    ): string {
        if ($length < 0) {
            throw new InvalidArgumentException(
                "length must be greater then 0"
            );
        }
        return static::splice(
            $target,
            $offset,
            $length,
            '',
        );
    }

    /*
    *   encode
    *
    *   @param string $string
    *   @param string $to_encoding
    *   @param ?string $from_encoding
    *   @return string
    */
    public static function encode(
        string $string,
        string $to_encoding,
        ?string $from_encoding,
    ): string {
        $result = mb_convert_encoding(
            $string,
            $to_encoding,
            $from_encoding,
        );

        if ($result === false) {
            throw new InvalidArgumentException(
                "encode error"
            );
        }
        return $result;
    }

    /*
    *   match
    *
    *   @param string $string
    *   @param string $pattern
    *   @param ?string $option
    *   @return bool
    */
    public static function match(
        string $string,
        string $pattern,
        ?string $option = 'pr',
    ): bool {
        return mb_ereg_match(
            $pattern,
            $string,
            $option,
        );
    }

    /*
    *   matchAll
    *
    *   @param string $string
    *   @param string $pattern
    *   @param ?string $option
    *   @return array
    */
    public static function matchAll(
        string $string,
        string $pattern,
        ?string $option = 'pr',
    ): array {
        if (!mb_ereg_search_init($string, $pattern, $options)) {
            throw new InvalidArgumentException(
                "matchAll init error"
            );
        }

        if (!mb_ereg_search_setpos(0)) {
            throw new InvalidArgumentException(
                "matchAll position error"
            );
        }

        $matches = [];
        while (($regs = mb_ereg_search_regs()) !== false) {
            $matches[] = $regs;
        }
        return $matches;
    }

    /*
    *   implode
    *
    *   @param array $array
    *   @param string $separator
    *   @return string
    */
    public static function implode(
        array $array,
        string $separator,
    ): string {
        $result = array_shift($array);

        foreach ($array as $string) {
            $result .= "{$separator}{$string}";
        }
        return $result;
    }

    /*
    *   explode
    *
    *   @param string $string
    *   @param string $separator
    *   @return array
    */
    public static function explode(
        string $string,
        string $separator,
    ): array {
        return static::split(
            $string,
            $separator,
        );
    }

    /*
    *   replace
    *
    *   @param string $string
    *   @param string $pattern
    *   @param string $replacement
    *   @param ?string $option
    *   @return string
    */
    public static function replace(
        string $string,
        string $pattern,
        string $replacement,
        ?string $option = 'pr',
    ): string {
        $result = mb_ereg_replace(
            $pattern,
            $replacement,
            $string,
            $option,
        );

        if ($result === false) {
            throw new InvalidArgumentException(
                "replace error"
            );
        }

        if ($result === null) {
            throw new InvalidArgumentException(
                "encoding error"
            );
        }
        return $result;
    }

    /*
    *   splice
    *
    *   @param string $string
    *   @param int $offset
    *   @param ?iint $length
    *   @param ?string $replacement
    *   @return string
    */
    public static function splice(
        string $string,
        int $offset,
        ?int $length = null,
        ?string $replacement = null,
    ): string {
        $target = static::strToArray($string);
        $len = $length ?? count($string);

        array_splice(
            $target,
            $offset,
            $len,
            $replacement
        );
        return static::implode('', $target);
    }

    /*
    *   substr
    *
    *   @param string $string
    *   @param int $start
    *   @param ?int $length
    *   @return string
    */
    public static function substr(
        string $string,
        int $start,
        ?int $length = null,
    ): string {
        return mb_substr(
            $string,
            $start,
            $length,
        );
    }

    /*
    *   split
    *
    *   @param string $string
    *   @param string $pattern
    *   @return array
    */
    public static function split(
        string $string,
        string $pattern,
    ): array {
        $result = mb_split(
            $string,
            $pattern,
        );

        if ($result === false) {
            throw new InvalidArgumentException(
                "sprit error"
            );
        }
        return $result;
    }

    /*
    *   search
    *
    *   @param string $string
    *   @param string $pattern
    *   @param ?string $option
    *   @return array [[potision, length],...]
    */
    public static function search(
        string $string,
        string $pattern,
        ?string $option = 'pr',
    ): array {
        if (!mb_ereg_search_init($string, $pattern, $options)) {
            throw new InvalidArgumentException(
                "matchAll init error"
            );
        }

        if (!mb_ereg_search_setpos(0)) {
            throw new InvalidArgumentException(
                "matchAll position error"
            );
        }

        $matches = [];
        while (
            (list($potision, $length) =
                mb_ereg_search_pos()
            ) !== false
        ) {
            $matches[] = compact($potision, $length);
        }
        return $matches;
    }

    /*
    *   isEmpty
    *
    *   @param string $string
    *   @return bool
    */
    public static function isEmpty(
        string $string,
    ): bool {
        return $string === '';
    }

    /*
    *   isValidEncoding
    *
    *   @param string $string
    *   @param ?string $encoding
    *   @return bool
    */
    public static function isValidEncoding(
        string $string,
        ?string $encoding = null,
    ): bool {
        return (bool)mb_detect_encoding(
            $string,
            $encoding ?? ini_get('default_charset'),
            true
        );
    }

    /**
    *   文字を1文字毎配列変換
    *
    *   @param string $string
    *   @param ?string ?$encoding
    *   @return array
    **/
    public static function strToArray(
        string $string,
        ?string $encoding = 'UTF-8',
    ): array {
        $result = preg_split(
            "//u",
            $string,
            0,
            PREG_SPLIT_NO_EMPTY
        );

        if ($result === false) {
            throw new InvalidArgumentException(
                "sprit error"
            );
        }
        return $result;
    }
}
