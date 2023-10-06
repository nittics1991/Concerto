<?php

/**
*   TableArrayUtil
*
*   @version 210903
*/

declare(strict_types=1);

namespace candidate\arrays;

use InvalidArgumentException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use candidate\arrays\OneDimensionArrayUtilTrait;

trait TableArrayUtilTrait
{
    use OneDimensionArrayUtilTrait;

    /**
    *   転置行列
    *
    *   @param mixed[] $array
    *   @return mixed[]
    *   @throws InvalidArgumentException
    */
    public static function transverse(array $array): array
    {
        if (!static::isDimension($array, 2)) {
            throw new InvalidArgumentException("data type is different");
        }

        foreach ($array as $name => $list) {
            foreach ($list as $key => $val) {
                $values[$key][$name] = $val;
            }
        }
        return (empty($values)) ? [[]] : $values;
    }

    /**
    *   flatten
    *
    *   @param mixed[] $array
    *   @param int $depth
    *   @return mixed[]
    */
    public static function flatten(array $array, int $depth = 1): array
    {
        return array_reduce(
            $array,
            function ($carry, $item) use ($depth) {
                if (! is_array($item)) {
                    return array_merge($carry, [$item]);
                }
                if ($depth === 1) {
                    return array_merge($carry, array_values($item));
                }
                return array_merge(
                    $carry,
                    static::flatten($item, $depth - 1)
                );
            },
            []
        );
    }

    /**
    *   列＝＞行展開
    *
    *   @param mixed[] $array
    *   @param string $key_column キーとする列
    *   @param string $val_column 値とする列
    *   @param callable $callback 重複処理 function($key_columns, $val_column){
    *                                   return [key1=>val1, ・・・];
    *                               }
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example expansion(['adr' => 'tokyo','month' => 1, 'data' => 23]
    *               , ['adr' => 'tokyo','month' => 2, 'data' => 11]]
    *               , 'month', 'data');
    *           ==> [1 => 23, 2 => 11]
    */
    public static function expansion(
        array $array,
        $key_column,
        $val_column,
        $callback = 'array_combine'
    ): array {
        if (
            !static::isDimension($array) ||
                !array_key_exists($key_column, $array[key($array)]) ||
                !array_key_exists($val_column, $array[key($array)]) ||
                !is_callable($callback)
        ) {
            throw new InvalidArgumentException("data type is different");
        }

        $transverse = static::transverse($array);
        return call_user_func(
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
    *   @example alignKey([['A'=>1,'B'=>2], ['B'=>12,'C'=>13], ...])
    *       ==> [['A'=>1,'B'=>2,'C'=>null], ['A'=>null,'B'=>12,'C'=>13]]
    */
    public static function alignKey(array $array = []): array
    {
        if ($array == [[]]) {
            return [[]];
        }

        if (($base = static::mergeKeyArray($array)) == false) {
            throw new InvalidArgumentException("data type is different");
        }

        $items = [];

        foreach ($array as $list) {
            array_push($items, array_merge($base, $list));
        }
        return $items;
    }

    /**
    *   階段積み上げ演算
    *
    *   @param mixed[] $array
    *   @param callable $callback 演算 ==>function($val, $previous)
    *   @param mixed $initial 初期値
    *   @return mixed[]
    *   @example stepwise([1,2,3], 'SUM') ==> [1,3,6]
    *               stepwise([1,2,3], 'SUM', 1) ==> [2,4,7]
    */
    public static function stepwise(
        array $array,
        callable $callback,
        $initial = null
    ): array {
        $items = [];
        $previous = $initial;

        foreach ($array as $val) {
            array_push(
                $items,
                ($previous = call_user_func($callback, $previous, $val))
            );
        }
        return $items;
    }

    /**
    *   空白行を埋める
    *
    *   @param mixed[] $array
    *   @param string $subscript 対象カラム
    *   @param mixed[] $keys 検索キー
    *   @param mixed[] $replace 置換行データ
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example $array= ['A'=>1,'B'=>11], ,['A'=>3,'B'=>33]];
    *               toFillBlank($array, 'A', [1,2,3,4], ['A'=>0,'B'=>0]);
    *               ==> [['A'=>1,'B'=>11], ['A'=>3,'B'=>33]]
    *                   , ['A'=>2,'B'=>0], ['A'=>4,'B'=>0]]
    */
    public static function toFillBlank(
        array $array,
        $subscript,
        array $keys = null,
        array $replace = null
    ): array {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException("data type is different");
        }

        if (!array_key_exists($subscript, $array[key($array)])) {
            throw new InvalidArgumentException("data type is different");
        }

        $transverse = static::transverse($array);

        if (is_null($keys) || !is_array($keys)) {
            $max = intval(max($transverse[$subscript]));
            $min = intval(min($transverse[$subscript]));
            $k = range($min, $max, 1);
        } else {
            $k = $keys;
        }

        if (is_null($replace) || !is_array($replace)) {
            $r = array_map(
                function ($val) {
                    return null;
                },
                array_flip(array_keys($array[key($array)]))
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

    /**
    *   テーブル行データから新しいカラムデータを作る
    *
    *   @param mixed[] $array
    *   @param callable $callback
    *   @return mixed[]
    *   @example $array= [
    *           ['A' => 10, 'B' => 400, 'C' => 1],
    *           ['A' => 20, 'B' => 300, 'C' => 2],
    *           ['A' => 30, 'B' => 200, 'C' => 3],
    *           ['A' => 40, 'B' => 100, 'C' => 4],
    *       ]
    *       $callback=function($row){
    *           return [
    *               'AB' => $row['A'] + $row['B'],
    *               'AC' => $row['A'] + $row['C'],
    *           ];
    *       }
    *       ==> [
    *           ['AB' => 410, 'AC' => 11],
    *           ['AB' => 320, 'AC' => 22],
    *           ['AB' => 230, 'AC' => 33],
    *           ['AB' => 140, 'AC' => 44],
    *       ];
    */
    public static function makeColumnFromRow(
        array $array,
        callable $callback
    ): array {
        $columns = [];
        foreach ($array as $list) {
            $columns[] = $callback($list);
        }
        return $columns;
    }

    /**
    *   指定列のみ持つテーブルに変換
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys 抽出キー
    *    @param mixed[] $default 存在しないキーの値 ['key' => val]
    *   @throws InvalidArgumentException
    *   @return mixed[]
    */
    public static function selectBy(
        array $array,
        array $keys,
        array $default = []
    ): array {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException("data type is different");
        }

        $length = count((array)current($array));
        $transverse = static::transverse($array);
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = (array_key_exists($key, $transverse)) ?
                $transverse[$key]
                : array_fill(
                    0,
                    $length,
                    (array_key_exists($key, $default)) ? $default[$key] : null
                );
        }
        return static::transverse($result);
    }

    /**
    *   指定列を除いたテーブルに変換
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys 抽出キー
    *   @throws InvalidArgumentException
    *   @return mixed[]
    */
    public static function unselectBy(array $array, array $keys): array
    {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException("data type is different");
        }

        return static::transverse(
            static::unextractKey(static::transverse($array), $keys)
        );
    }

    /**
    *   並び替え
    *
    *   @param mixed[] $array
    *   @param mixed[] $columns グループカラム
    *   @param mixed[] $orders 並べ替え方向(ex:array_multisort order)
    *   @param mixed[] $sorts 並べ替え方法(ex:array_multisort flags)
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example orderBy([['A' =>1, 'B' =>2], ['A' =>11, 'B' =>12]]
    *           , ['B'], [SORT_ASC], [SORT_NUMERIC ]);
    */
    public static function orderBy(
        array $array,
        $columns = null,
        $orders = null,
        $sorts = null
    ): array {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException("data type is different");
        }

        if (is_array($columns)) {
            $col = $columns;
        } elseif (is_null($columns)) {
            $col = array_keys($array[key($array)]);
        } else {
            throw new InvalidArgumentException("data type is different");
        }

        if (is_array($orders) && (count($orders) == count($col))) {
            $odr = $orders;
        } elseif (is_null($orders)) {
            $odr = array_fill(0, count($col), SORT_ASC);
        } else {
            throw new InvalidArgumentException("data type is different");
        }

        if (is_array($sorts) && (count($sorts) == count($col))) {
            $srt = $sorts;
        } elseif (is_null($sorts)) {
            $srt = array_fill(0, count($col), SORT_REGULAR);
        } else {
            throw new InvalidArgumentException("data type is different");
        }

        $transverse = static::transverse($array);

        if (!isset($array[key($array)])) {
            throw new InvalidArgumentException("key not exists");
        }

        $keys = array_keys($array[key($array)]);
        $result = $array;

        $eval = 'array_multisort(';
        for (
            $i = 0, $length = count($col);
            $i < $length;
            $i++
        ) {
            if (in_array($col[$i], $keys)) {
                $eval .= '$transverse[\'' . $col[$i] . '\'], ';
                $eval .= (int)$odr[$i] . ', ';
                $eval .= (int)$srt[$i] . ', ';
            }
        }
        $eval .= '$result);';

        eval($eval);
        return $result;
    }

    /**
    *   集約
    *
    *   @param mixed[] $array
    *   @param mixed[] $columns グループカラム
    *   @param mixed[] $callback 集約演算の対象カラムと関数
    *           ['column1' => function(){}, 'column2' => 'array_sum']
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example groupBy([['A' =>1, 'B' =>2], ['A' =>11, 'B' =>12]]
    *       , ['B'], ['A' => function($array){return array_sum($array);}]);
    */
    public static function groupBy(
        array $array,
        array $columns,
        array $callback
    ): array {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException(
                "data type is different:array"
            );
        }

        $keys = array_keys($array[key($array)]);
        if (!is_array($columns)) {
            throw new InvalidArgumentException(
                "data type is different:columns"
            );
        }

        foreach ($columns as $key) {
            if (!in_array($key, $keys)) {
                throw new InvalidArgumentException(
                    "data type is different:columns"
                );
            }
        }

        if (!is_array($callback)) {
            throw new InvalidArgumentException(
                "data type is different:callback"
            );
        }

        foreach ($callback as $key => $func) {
            if (!in_array($key, $keys) || !is_callable($func)) {
                throw new InvalidArgumentException(
                    "data type is different:callback"
                );
            }
        }

        $order = static::orderBy($array, $columns);

        $aggregate = [];
        $group = [];
        $previous = [];
        $i = 0;

        foreach ($order as $list) {
            $select = static::extractKey($list, $columns);

            if ($select != $previous) {
                $i++;
                $group[$i] = $select;
            }

            foreach ($callback as $key => $func) {
                if (isset($list[$key])) {
                    $aggregate[$i][$key][] = $list[$key];
                }
            }

            $previous = $select;
        }

        $result = [];

        foreach ($aggregate as $no => $row) {
            $items = [];

            foreach ($row as $key => $list) {
                $items[$key] = call_user_func($callback[$key], $list);
            }

            array_push($result, array_merge($group[$no], $items));
        }
        return $result;
    }

    /**
    *   クロス集計
    *
    *   @param mixed[] $array
    *   @param string $row_key 行とする列
    *   @param string $column_key 列とする列
    *   @param mixed[] $callback 各列集約の関数 [col1 => callbacl1,
    *                   col3 => callback3, ・・・]
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example $array ==>
    *       a b   c  d
    *       1 tel 13 14
    *       1 tel 11 12
    *       2 tel 17 18
    *       2 adr 15 16
    *       2 tel 19 20
    *
    *       groupBy($array, ['b', 'a'[, ['c'=>'array_sum'])
    *       a b   c  d
    *       1 tel 24 26
    *       2 adr 15 16
    *       2 tel 36 38
    *
    *       pivot($array, 'b', 'a', ['c'=>'array_sum'])
    *       c    1  2
    *       adr  0 15
    *       tel  24 36
    *
    *       ==> [
    *               0 => ['b' => 'a', 1 => 1, 2 => 1],
    *               'c' => [
    *                   ['b' => 'adr', 1 => 15, 2 => 0],
    *                   ['b' => 'tel', 1 => 24, 2 => 36]
    *               ]
    *           ]
    */
    public static function pivot(
        array $array,
        $row_key,
        $column_key,
        array $callback
    ): array {
        $keys = array_keys($array[key($array)]);
        if (!is_array($callback)) {
            throw new InvalidArgumentException(
                "data type is different:callback"
            );
        }

        foreach ($callback as $key => $func) {
            if (!in_array($key, $keys) || !is_callable($func)) {
                throw new InvalidArgumentException(
                    "data type is different:{$key}"
                );
            }
        }

        $groupBy = static::groupBy(
            $array,
            [$row_key, $column_key],
            $callback
        );

        if (count($groupBy) == 0) {
            return [];
        }

        $transverse = static::transverse($groupBy);
        $rows = array_unique($transverse[$row_key]);
        $columns = array_unique($transverse[$column_key]);
        $tables = array_keys($callback);

        natsort($rows);
        natsort($columns);

        $dataset = static::orderBy(
            $groupBy,
            array_merge(
                [$row_key, $column_key],
                $tables
            )
        );

        $title_columns = array_merge([$row_key], $columns);
        $result = [array_combine($title_columns, $title_columns)];
        $result[0][$row_key] = $column_key;

        $c = current($dataset);

        foreach ($tables as $table) {
            $result[$table] = [];
            $initial[$table] = call_user_func($callback[$table], []);
        }

        $items = [];

        foreach ($rows as $row) {
            foreach ($tables as $table) {
                $items[$table] = [$row_key => $row];
            }

            foreach ($columns as $col) {
                if ($c === false) {
                    foreach ($tables as $table) {
                        $items[$table][$col] = $initial[$table];
                    }
                } elseif (
                    in_array($col, $c, true) &&
                    in_array($row, $c, true)
                ) {
                    foreach ($tables as $table) {
                        $items[$table][$col] = $c[$table];
                    }
                    $c = next($dataset);
                } else {
                    foreach ($tables as $table) {
                        $items[$table][$col] = $initial[$table];
                    }
                }
            }
            foreach ($tables as $table) {
                $result[$table][] = $items[$table];
            }
        }
        return $result;
    }

    /**
    *   TABLE JOIN
    *
    *   @param mixed[] $table1
    *   @param mixed[] $table2
    *   @param mixed[] $where 結合条件 [['column1-m', 'column2-n'], ・・・]
    *   @param string $type 結合タイプ('left', 'inner')
    *   @param string $suffix
    *   @return mixed[]
    *   @throws InvalidArgumentException
    */
    public static function joinTable(
        array $table1,
        array $table2,
        array $where = [],
        string $type = 'inner',
        string $suffix = ''
    ): array {
        if (
            !static::isTable($table1) ||
            !static::isTable($table2) ||
            !in_array(strtolower($type), ['left', 'inner'])
        ) {
            throw new InvalidArgumentException("args type is different");
        }

        $result = [];
        $col2_keys = [];

        foreach ($table1 as $row1) {
            $count = 0;

            foreach ($table2 as $row2) {
                $join = true;

                if (empty($col2_keys)) {
                    $col2_keys = array_map(
                        function ($val) use ($suffix) {
                            return "{$val}{$suffix}";
                        },
                        array_keys($row2)
                    );
                }

                foreach ($where as $keys) {
                    if (count($keys) != 2) {
                        throw new InvalidArgumentException(
                            "args type is different"
                        );
                    }
                    if ($row1[$keys[0]] != $row2[$keys[1]]) {
                        $join = false;
                    }
                }

                if ($join) {
                    array_push(
                        $result,
                        array_merge(
                            $row1,
                            (array)array_combine($col2_keys, $row2)
                        )
                    );
                    $count++;
                }
            }

            if ((strtolower($type) == 'left') && ($count == 0)) {
                array_push(
                    $result,
                    array_merge(
                        $row1,
                        array_fill_keys($col2_keys, null)
                    )
                );
            }
        }
        return $result;
    }

    /**
    *   TABLE判定
    *
    *   @param mixed[] $array
    *   @param bool $is_key true:添字比較実行
    *   @param bool $is_type true:データ型比較実行
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

    /**
    *   isEmptyTable
    *
    *   @param mixed[] $array
    *   @return bool
    */
    public static function isEmptyTable(array $array): bool
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($array)
        );

        foreach ($iterator as $val) {
            if (!empty($val)) {
                return false;
            }
        }
        return true;
    }
}
