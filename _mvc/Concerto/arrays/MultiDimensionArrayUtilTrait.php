<?php

/**
*   TableArrayUtil
*
*   @version 230112
*/

declare(strict_types=1);

namespace Concerto\arrays;

use InvalidArgumentException;
use Concerto\arrays\{
    ComparisonArrayUtilTrait,
    OneDimensionArrayUtilTrait,
};

trait MultiDimensionArrayUtilTrait
{
    use OneDimensionArrayUtilTrait;

    /**
    *   転置行列
    *
    *   @param mixed[] $array
    *   @return mixed[]
    */
    public static function transverse(
        array $array
    ): array {
        foreach ($array as $name => $list) {
            foreach ((array)$list as $key => $val) {
                $values[$key][$name] = $val;
            }
        }
        return (empty($values)) ? [[]] : $values;
    }

    /**
    *   列＝＞行展開
    *
    *   @param mixed[] $array
    *   @param string|int $key_column キーとする列
    *   @param string|int $val_column 値とする列
    *   @param callable $callback 重複処理
    *       function($key_columns,$val_column){
    *           return [key1=>val1,・・・];
    *       }
    *   @return mixed[]
    *   @example expansion([
    *           ['adr' => 'tokyo','month' => 1, 'data' => 23],
    *           ['adr' => 'tokyo','month' => 2, 'data' => 11]
    *       ],
    *       'month',
    *       'data'
    *       );==> [1 => 23, 2 => 11]
    */
    public static function expansion(
        array $array,
        string|int $key_column,
        string|int $val_column,
        mixed $callback = 'array_combine'
    ): array {
        $transverse = static::transverse($array);
        return (array)call_user_func(
            $callback,
            $transverse[$key_column],
            $transverse[$val_column]
        );
    }

    /**
    *   キー揃え
    *
    *   @param mixed[] $array
    *   @return mixed[]
    *   @example alignKey(
    *       [['A'=>1,'B'=>2], ['B'=>12,'C'=>13], ...]
    *       ) ==> [
    *           ['A'=>1,'B'=>2,'C'=>null],
    *           ['A'=>null,'B'=>12,'C'=>13]
    *       ]
    */
    public static function alignKey(
        array $array = []
    ): array {
        if ($array === [[]]) {
            return [[]];
        }

        if (
            (
                $base = call_user_func_array(
                    static::class . '::mergeKey',
                    $array,
                )) === false
        ) {
            throw new InvalidArgumentException(
                "data type is different"
            );
        }

        $items = [];

        foreach ($array as $list) {
            array_push(
                $items,
                array_merge((array)$base, (array)$list)
            );
        }
        return $items;
    }

    /**
    *   階段積み上げ演算
    *
    *   @param mixed[] $array
    *   @param callable $callback function($val, $previous)
    *   @param mixed $initial 初期値
    *   @return mixed[]
    *   @example stepwise([1,2,3], 'SUM') ==> [1,3,6]
    *               stepwise([1,2,3], 'SUM', 1) ==> [2,4,7]
    */
    public static function stepwise(
        array $array,
        callable $callback,
        mixed $initial = null
    ): array {
        $items = [];
        $previous = $initial;

        foreach ($array as $val) {
            array_push(
                $items,
                ($previous = call_user_func(
                    $callback,
                    $previous,
                    $val
                )
                )
            );
        }
        return $items;
    }

    /**
    *   空白行を埋める
    *
    *   @param mixed[] $array
    *   @param string  $subscript 対象カラム
    *   @param mixed[] $keys      検索キー(対象カラムに必要な値)
    *   @param mixed[] $replace   置換行データ(不足行の値)
    *   @return mixed[]
    *   @example $array= [['A'=>1,'B'=>11], ,['A'=>3,'B'=>33]];
    *       //A列の値に[1,2,3,4]が必要で不足行['A'=>$key,B=>9]で追加
    *       toFillBlank($array, 'A', [1,2,3,4], ['B'=>9]);
    *       ==> [['A'=>1,'B'=>11], ['A'=>3,'B'=>33]
    *       , ['A'=>2,'B'=>9], ['A'=>4,'B'=>9]]
    */
    public static function toFillBlank(
        array $array,
        string|int $subscript,
        array $keys = null,
        array $replace = null
    ): array {
        $transverse = static::transverse($array);

        if (
            !isset($transverse[$subscript]) ||
            !is_array($transverse[$subscript])
        ) {
            return $array;
        }

        if (is_null($keys) || !is_array($keys)) {
            $max = intval(
                max((array)$transverse[$subscript])
            );

            $min = intval(
                min((array)$transverse[$subscript])
            );

            $k = range($min, $max, 1);
        } else {
            $k = $keys;
        }

        if (is_null($replace) || !is_array($replace)) {
            $r = array_map(
                function ($val) {
                    return null;
                },
                array_flip(
                    array_keys((array)$array[key($array)])
                )
            );
        } else {
            $r = $replace;
        }

        $result = $array;

        foreach ($k as $val) {
            if (!in_array($val, $transverse[$subscript])) {
                $ar = $r;
                $ar[$subscript] = $val;
                array_push($result, $ar);
            }
        }

        return $result;
    }

    /*
    *   指定キーに揃える
    *
    *   @param mixed[] $target
    *   @param (int[]|string[]) $keys [key1,...]
    *   @param mixed[] $complement
    *       [keyB => value2, keyX => callable($target),...]
    *       未指定:null
    *   @param bool $rest_removed
    *   @return mixed[]
    *   @example
    *       fillUp(range('a','e'),[0,2,9],false)
    *           ==> [0=>'a',2=>'c']
    *       fillUp(range('a','e'),[0,2,9],true)
    *           ==> [0=>'a',2=>'c',9=>null]
    *       fillUp(array_combine(range('a','e'),range('A','E')),
    *          ['a','c','y','z'],true,['z'=>fn($ar)=>ord($ar['z'])])
    *           ==> ['a'=>'A','c'=>'C','y'=>null,'z'=>90]
    */
    public static function fillUp(
        array $target,
        array $keys = [],
        array $complement = [],
        bool $rest_removed = true,
    ): array {
        $diff_keys = !$rest_removed ?
            array_diff(
                array_keys($target),
                $keys,
            ) :
            [];
        $fill_keys = array_merge($keys, $diff_keys);

        $result = [];

        foreach ($fill_keys as $key) {
            if (isset($target[$key])) {
                $result[$key] = $target[$key];
            } elseif (isset($complement[$key])) {
                $result[$key] = is_callable($complement[$key]) ?
                    call_user_func(
                        $complement[$key],
                        $target,
                    ) :
                    $complement[$key];
            } else {
                $result[$key] = null;
            }
        }
        return $result;
    }

    /*
    *   各列の行数を最大行数で揃える
    *       (Excel出力時に文字列セルを複数行にした表変換へ利用)
    *
    *   @param mixed[] $target
    *   @return mixed[]
    *   @example flatRow(['A'=>1,'B'=>[11,12,13],'C'=>[21,[22,23]]]
    *      ==>[['A'=>1,'B'=>null,'C'=>null],
    *          ['A'=>11,'B'=>12,'C'=>13],
    *          ['A'=>21,'B'=>[22,23],'C'=>null]
    */
    public static function flatRow(
        array $target,
    ): array {
        $splited = [];
        $column_names = [];
        $max_inner_row = 0;
        $cnt = 0;

        foreach ($target as $key => $value) {
            $splited[$cnt] = !is_array($value) ?
                [$value] : array_values($value);

            if (!in_array($key, $column_names)) {
                $column_names[] = $key;
            }

            $max_inner_row = max(
                $max_inner_row,
                count($splited[$cnt]),
            );

            $cnt++;
        }

        $aligned = [];

        foreach ($splited as $key => $columns) {
            if (count($columns) === $max_inner_row) {
                $aligned[] = array_values($columns);
            } else {
                $inners = [];
                for ($cnt = 0; $cnt < $max_inner_row; $cnt++) {
                    $inners[] = $columns[$cnt] ?? null;
                }
                $aligned[] = $inners;
            }
        }

        $result = [];

        for ($row = 0; $row < $max_inner_row; $row++) {
            $column = 0;

            foreach ($column_names as $name) {
                $result[$row][$name] = $aligned[$column][$row];
                $column++;
            }
        }
        return $result;
    }
}
