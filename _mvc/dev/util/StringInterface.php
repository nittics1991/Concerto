<?php

/**
*   StringInterface
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\util;

interface StringInterface
{
    /*
    *   lower
    *
    *   @param string $string
    *   @return string
    */
    public static function lower(
        string $string,
    ): string;

    /*
    *   upper
    *
    *   @param string $string
    *   @return string
    */
    public static function upper(
        string $string,
    ): string;

    /*
    *   title
    *
    *   @param string $string
    *   @return string
    */
    public static function title(
        string $string,
    ): string;

    /*
    *   study
    *
    *   @param string $string
    *   @return string
    */
    public static function study(
        string $string,
    ): string;

    /*
    *   camel
    *
    *   @param string $string
    *   @return string
    */
    public static function camel(
        string $string,
    ): string;

    /*
    *   snake
    *
    *   @param string $string
    *   @return string
    */
    public static function snake(
        string $string,
    ): string;

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
    ): string;

    /*
    *   length
    *
    *   @param string $string
    *   @return int
    */
    public static function length(
        string $string,
    ): int;

    /*
    *   width
    *
    *   @param string $string
    *   @return int
    */
    public static function width(
        string $string,
    ): int;

    /*
    *   trim
    *
    *   @param string $string
    *   @return string
    */
    public static function trim(
        string $string,
    ): string;

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
    ): string;

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
    ): string;

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
    ): string;

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
        ?string $option,
    ): bool;

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
        ?string $option,
    ): array;

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
    ): string;

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
    ): array;

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
        ?string $option,
    ): string;

    /*
    *   split
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
        ?int $length,
        ?string $replacement,
    ): string;

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
        ?int $length,
    ): string;

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
    ): array;

    /*
    *   search
    *
    *   @param string $string
    *   @param string $pattern
    *   @param ?string $option
    *   @return array
    */
    public static function search(
        string $string,
        string $pattern,
        ?string $option,
    ): array;

    /*
    *   isEmpty
    *
    *   @param string $string
    *   @return bool
    */
    public static function isEmpty(
        string $string,
    ): bool;

    /*
    *   isValidEncoding
    *
    *   @param string $string
    *   @param ?string $encoding
    *   @return bool
    */
    public static function isValidEncoding(
        string $string,
        ?string $encoding,
    ): bool;

    /**
    *   文字を1文字毎配列変換
    *
    *   @param string $string
    *   @param ?string ?$encoding
    *   @return array
    **/
    public static function strToArray(
        string $string,
        ?string $encoding,
    ): array;
}
