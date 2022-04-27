<?php

/**
*   マルチバイト文字
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\mbstring;

use InvalidArgumentException;

class MbString
{
    /**
    *   バイト長
    *
    *   @param string $data
    *   @return int
    */
    public static function byteLength(string $data): int
    {
        return mb_strlen($data, '8bit');
    }

    /**
    *   文字列削除
    *
    *   @param string $target
    *   @param int $offset 開始位置
    *   @param int $length 文字数
    *   @param string $encoding エンコード
    *   @return string|false
    */
    public static function delete(
        string $target,
        int $offset,
        int $length = 1,
        string $encoding = 'UTF-8'
    ) {
        if ($length < 0) {
            return false;
        }
        return static::splice($target, $offset, $length, '', $encoding);
    }

    /**
    *   正規表現全マッチ
    *
    *   @param string $pattern パターン
    *   @param string $string 対象
    *   @param string $option オプション @see mb_regex_set_options
    *   @return mixed[] マッチ文字情報 @see mb_ereg_search_regs
    */
    public static function eregMatchAll(
        string $pattern,
        string $string,
        $option = 'msr'
    ): array {
        mb_ereg_search_init($string);
        $counter = mb_strlen($string);
        $result = [];

        while (
            ($ans = mb_ereg_search_regs($pattern, $option)) !== false &&
            $counter >= 0
        ) {
            $result[] = $ans;
            mb_ereg_search_setpos(mb_ereg_search_getpos());
            $counter--;
        }
        return $result;
    }

    /**
    *   5C文字エスケープ
    *
    *   @param string $string
    *   @return string|false
    */
    public static function escape5c(string $string)
    {
        $result = $string;

        array_walk(
            MbStringConst::$sjis5c,
            function (&$val, $key) use (&$result) {
                $result = (string)mb_ereg_replace(
                    $val,
                    "{$val}\\",
                    (string)$result
                );
            }
        );
        return $result;
    }

    /**
    *   mb_expode
    *
    *   @param string $delimiter 区切り文字
    *   @param string $string 文字列
    *   @param int $limit 配列要素数
    *   @param string $encoding エンコード
    *   @return string[]
    */
    public static function explode(
        string $delimiter,
        string $string,
        int $limit = -1,
        string $encoding = 'UTF-8'
    ): array {
        $tmp = mb_regex_encoding();

        mb_regex_encoding($encoding);
        $delimiter = (string)mb_ereg_replace(
            '[.\\\\+*?\\[^$(){}|]',
            '\\\\0',
            $delimiter
        );
        $ret = mb_split($delimiter, $string, $limit);

        mb_regex_encoding($tmp);

        return $ret === false ? [] : $ret;
    }

    /**
    *   文字列挿入
    *
    *   @param string $target
    *   @param int $offset 開始位置
    *   @param string $string 挿入文字列
    *   @param string $encoding エンコード
    *   @return string
    */
    public static function insert(
        string $target,
        int $offset,
        string $string,
        string $encoding = 'UTF-8'
    ): string {
        return static::splice($target, $offset, 0, $string, $encoding);
    }

    /**
    *   文字列に5C文字が含むか確認
    *
    *   @param string $string
    *   @return bool
    */
    public static function is5c(string $string): bool
    {
        $cnt = 0;

        array_walk(
            MbStringConst::$sjis5c,
            function (&$val, $key) use (&$cnt, $string) {
                $cnt += mb_substr_count($string, $val);
            }
        );
        return $cnt > 0;
    }

    /**
    *   mb_replace
    *
    *   @param string $search 検索
    *   @param string $replace 置換
    *   @param string $subject 対象
    *   @param string $encoding エンコード
    *   @return string|false
    */
    public static function replace(
        string $search,
        string $replace,
        string $subject,
        string $encoding = 'UTF-8'
    ) {
        $tmp = mb_regex_encoding();
        mb_regex_encoding($encoding);

        foreach ((array)$search as $s) {
            $s = (string)mb_ereg_replace('[.\\\\+*?\\[^$(){}|]', '\\\\0', $s);
            $subject = (string)mb_ereg_replace($s, $replace, (string)$subject);
        }

        mb_regex_encoding($tmp);
        return $subject;
    }

    /**
    *   部分文字列抽出置換
    *
    *   @param string $string 対象文字列
    *   @param int $offset 開始位置
    *   @param ?int $length 文字数
    *   @param string $replacement 置換文字列
    *   @param string $encoding エンコード
    *   @return string
    */
    public static function splice(
        string $string,
        int $offset,
        ?int $length = null,
        string $replacement = '',
        string $encoding = 'UTF-8'
    ): string {
        $target = MbString::strToArray($string, $encoding);
        $len = (is_null($length)) ?  count($target) : $length;

        array_splice($target, $offset, $len, $replacement);
        return implode('', $target);
    }

    /**
    *   mb_split
    *
    *   @param string $string 文字列
    *   @param int $split_length 区切り文字数
    *   @param string $encoding エンコード
    *   @return array|false
    */
    public static function split(
        string $string,
        int $split_length = 1,
        string $encoding = 'UTF-8'
    ) {
        return mb_str_split(
            $string,
            $split_length,
            $encoding,
        );
    }

    /**
    *   文字を1文字毎配列変換
    *
    *   @param string $string 対象文字列
    *   @param string $encoding エンコード
    *   @return array
    */
    public static function strToArray(
        string $string,
        string $encoding = 'UTF-8'
    ): array {
        $splited = static::split(
            $string,
            1,
            $encoding,
        );
        return $splited === false ?
            [] : $splited;
    }

    /**
    *   TAB=>空白変換
    *
    *   @param string $string 対象文字列
    *   @param int $length TAB幅
    *   @param ?string $encoding エンコーディング
    *   @return string
    */
    public static function tab2space(
        string $string,
        int $length = 4,
        ?string $encoding = null
    ): string {
        if (!is_int($length) || ($length < 0)) {
            throw new InvalidArgumentException("required integer >0");
        }

        $encoding = is_null($encoding) ? mb_internal_encoding() : $encoding;
        $result = '';
        $haystack = $string;
        while (false !== ($pos = mb_strpos($haystack, "\t", 0, $encoding))) {
            $result .= mb_substr($haystack, 0, $pos, $encoding);
            $result .= str_repeat(' ', ($length - ($pos % $length)));
            $haystack = mb_substr($haystack, $pos + 1, null, $encoding);
        }
        $result .= $haystack;
        return $result;
    }

    /**
    *   toLowerCamel
    *
    *   @param string $string
    *   @return string
    */
    public static function toLowerCamel(string $string): string
    {
        $uppered = MbString::toUpperCamel($string);
        return mb_strtolower(mb_substr($uppered, 0, 1)) .
            mb_substr($uppered, 1);
    }

    /**
    *   toSnake
    *
    *   @param string $string
    *   @return string
    *   @caution
    *       '_mst_Bumon_data' ==> '_mst__bumon_data' //unsder scoreはそのまま残る
    *       'mstBumon_Data' ==> 'mst_bumon__data' //_Data ==> __dataとなる
    */
    public static function toSnake(string $string): string
    {
        $replaced = (string)mb_ereg_replace_callback(
            '[A-Z]',
            function ($matches) {
                return '_'  . mb_strtolower($matches[0]);
            },
            $string
        );

        if (
            mb_substr($replaced, 0, 1) == '_' &&
            mb_substr($string, 0, 1) != '_'
        ) {
            return mb_substr($replaced, 1);
        }
        return $replaced;
    }

    /**
    *   toUpperCamel
    *
    *   @param string $string
    *   @return string
    */
    public static function toUpperCamel(string $string): string
    {
        $snaked = MbString::toSnake($string);
        $replaced = (string)mb_ereg_replace('_', ' ', $snaked);
        $titled = mb_convert_case($replaced, MB_CASE_TITLE);
        return implode('', explode(' ', $titled));
    }

    /**
    *   mb_trim
    *
    *   @param string $str 文字列
    *   @param ?string $charlist マスク文字
    *   @param string $encoding エンコード
    *   @return string|false
    */
    public static function trim(
        string $str,
        ?string $charlist = null,
        string $encoding = 'UTF-8'
    ) {
        $tmp = mb_regex_encoding();
        mb_regex_encoding($encoding);
        $charlist = is_null($charlist) ?
           mb_convert_encoding(" \t\n\r\0　\x0B", $encoding, 'UTF-8') :
            $charlist;

        $pattern = "[{$charlist}]";
        $ret = (string)mb_ereg_replace("\A{$pattern}+", '', $str);
        $ret = (string)mb_ereg_replace("{$pattern}+\z", '', $ret);

        mb_regex_encoding($tmp);
        return $ret;
    }

    /**
    *   エンコードチェック
    *
    *   @param string $string
    *   @return bool
    */
    public static function validEncodeName(string $string): bool
    {
        $encodings = mb_list_encodings();
        $aliases = array_map('mb_encoding_aliases', $encodings);
        $valid_encodings = array_reduce($aliases, 'array_merge', $encodings);
        return in_array($string, $valid_encodings);
    }
}
