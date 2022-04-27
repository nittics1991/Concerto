<?php

/**
*   ComparisonArrayUtilTrait
*
*   @version 220222
*/

declare(strict_types=1);

namespace Concerto\arrays;

use Traversable;

trait ComparisonArrayUtilTrait
{
    /**
    *   次元判定
    *
    *   @param mixed $array
    *   @param int   $dimension 判定次元
    *   @param int   $current   現在次元
    *   @return bool
    */
    public static function isDimension(
        $array,
        int $dimension = 2,
        int $current = 1
    ): bool {
        $ans = is_array($array);

        if ($ans && ($current < $dimension)) {
            foreach ($array as $list) {
                $ans = $ans &&
                    static::isDimension($list, $dimension, $current + 1);
            }
        }
        return $ans;
    }

    /**
    *   再帰比較
    *
    *   @param mixed $x 比較対象1
    *   @param mixed $y 比較対象2
    *   @return mixed[]|false 結果 ['key' => [$x1, $y1]]
    *   @example compare(
    *       ['a' => ['aa' => 1, 'ab' => 2]], 'b' => 3],
    *       ['a' => ['aa' => 1, 'ab' => 12]], 'b' => 3])
    *       ==> ['a']['ab'] = [2, 12]
    */
    public static function compare($x, $y)
    {
        if (is_array($x) && is_array($y)) {
            $result = [];
            foreach ($x as $key => $val) {
                $comp = self::compare($x[$key], $y[$key]);
                if (!empty($comp)) {
                    $result = array_merge($result, [$key => $comp]);
                }
            }
            return $result;
        }

        if ($x instanceof Traversable && $y instanceof Traversable) {
            $result = [];
            foreach ($x as $key => $val) {
                $comp = self::compare($x->$key, $y->$key);
                if (!empty($comp)) {
                    $result = array_merge($result, [$key => $comp]);
                }
            }
            return $result;
        }

        if (
            !is_array($x)
            && !is_array($y)
            && !is_object($x)
            && !is_object($y)
        ) {
            if ($x === $y) {
                return [];
            }
            return [$x, $y];
        }
        return false;
    }

    /**
    *   key構造が同じ(key順序・型が同一)
    *
    *   @param mixed[] $array1
    *   @param mixed[] $array2
    *   @return bool
    */
    public static function sameStruct(array $array1, array $array2): bool
    {
        $typemap1 = array_map(
            function ($val) {
                return gettype($val);
            },
            $array1
        );

        $typemap2 = array_map(
            function ($val) {
                return gettype($val);
            },
            $array2
        );
        return $typemap1 === $typemap2 &&
            array_keys($array1) == array_keys($array2);
    }

    /**
    *   keyが同じ(key順序・型が同一)
    *
    *   @param mixed[] $array1
    *   @param mixed[] $array2
    *   @return bool
    */
    public static function sameKeys(array $array1, array $array2): bool
    {
        $keys1 = array_keys($array1);
        $keys2 = array_keys($array2);

        $maege = array_merge(
            array_diff($keys1, $keys2),
            array_diff($keys2, $keys1)
        );
        return empty($maege);
    }

    /**
    *   TABLE判定
    *
    *   @param mixed[] $array
    *   @param bool    $is_key  true:添字比較実行
    *   @param bool    $is_type true:データ型比較実行
    *   @return bool
    */
    public static function isTable(
        array $array,
        bool $is_key = true,
        bool $is_type = true
    ): bool {
        $mem = null;
        foreach ($array as $list) {
            if (is_null($mem)) {
                $mem = $list;
            } else {
                if ($is_type) {
                    if (!static::sameStruct($mem, $list)) {
                        return false;
                    }
                } elseif ($is_key) {
                    if (!static::sameKeys($mem, $list)) {
                        return false;
                    }
                } else {
                    if (count($list) != count($mem)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
}
