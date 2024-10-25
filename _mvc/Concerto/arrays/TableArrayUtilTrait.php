<?php

/**
*   TableArrayUtil
*
*   @version 221130
*/

declare(strict_types=1);

namespace Concerto\arrays;

use InvalidArgumentException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Concerto\arrays\{
    ComparisonArrayUtilTrait,
    MultiDimensionArrayUtilTrait,
};

trait TableArrayUtilTrait
{
    use ComparisonArrayUtilTrait;
    use MultiDimensionArrayUtilTrait;

    /**
    *   指定列のみ持つテーブルに変換
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys    抽出キー
    *   @param mixed[] $default 存在しないキーの値 ['key' => val]
    *   @throws InvalidArgumentException
    *   @return mixed[]
    */
    public static function selectBy(
        array $array,
        array $keys,
        array $default = []
    ): array {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException(
                "data type is different"
            );
        }

        $length = count((array)current($array));
        $transverse = static::transverse($array);
        $result = [];

        foreach ($keys as $key) {
            if (
                !is_int($key) &&
                !is_string($key)
            ) {
                throw new InvalidArgumentException(
                    "keys must be int|string"
                );
            }

            $result[$key] = array_key_exists($key, $transverse) ?
                $transverse[$key]
                : array_fill(
                    0,
                    $length,
                    (array_key_exists($key, $default)) ?
                        $default[$key] : null
                );
        }
        return static::transverse($result);
    }

    /**
    *   並び替え
    *
    *   @param mixed[] $array
    *   @param mixed[] $columns グループカラム
    *   @param mixed[] $orders  並べ替え方向(ex:array_multisort
    *                           order)
    *   @param mixed[] $sorts   並べ替え方法(ex:array_multisort
    *                           flags)
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example orderBy([['A' =>1, 'B' =>2], ['A' =>11, 'B' =>12]]
    *           , ['B'], [SORT_ASC], [SORT_NUMERIC ]);
    */
    public static function orderBy(
        array $array,
        array $columns = null,
        array $orders = null,
        array $sorts = null
    ): array {
        if (!static::isDimension($array)) {
            throw new InvalidArgumentException(
                "data type is different"
            );
        }

        if (is_array($columns)) {
            $col = $columns;
        } elseif (is_null($columns)) {
            $col = array_keys((array)$array[key($array)]);
        } else {
            throw new InvalidArgumentException(
                "data type is different"
            );
        }

        if (
            is_array($orders) &&
            (count($orders) === count($col))
        ) {
            $odr = $orders;
        } elseif (is_null($orders)) {
            $odr = array_fill(0, count($col), SORT_ASC);
        } else {
            throw new InvalidArgumentException(
                "data type is different"
            );
        }

        if (
            is_array($sorts) &&
            (count($sorts) === count($col))
        ) {
            $srt = $sorts;
        } elseif (is_null($sorts)) {
            $srt = array_fill(0, count($col), SORT_REGULAR);
        } else {
            throw new InvalidArgumentException(
                "data type is different"
            );
        }

        $transverse = static::transverse($array);

        if (!isset($array[key($array)])) {
            throw new InvalidArgumentException(
                "key not exists"
            );
        }

        $keys = array_keys((array)$array[key($array)]);
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
    *   @param mixed[] $columns  グループカラム
    *   @param mixed[] $callback 集約演算の対象カラムと関数
    *       ['column1' => function(){}, 'column2' => 'array_sum']
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

        $keys = array_keys((array)$array[key($array)]);
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
            if (
                !in_array($key, $keys) ||
                !is_callable($func)
            ) {
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
            if (!is_array($list)) {
                throw new InvalidArgumentException(
                    "data is not dimensions"
                );
            }

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
                $items[$key] = call_user_func(
                    $callback[$key],
                    $list
                );
            }

            array_push(
                $result,
                array_merge($group[$no], $items)
            );
        }
        return $result;
    }

    /**
    *   クロス集計
    *
    *   @param mixed[] $array
    *   @param string  $row_key    行とする列
    *   @param string  $column_key 列とする列
    *   @param mixed[] $callback   各列集約の関数 [col1
    *                              => callbacl1, col3 =>
    *                              callback3, ・・・]
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
        string $row_key,
        string $column_key,
        array $callback
    ): array {
        $keys = array_keys((array)$array[key($array)]);
        if (!is_array($callback)) {
            throw new InvalidArgumentException(
                "data type is different:callback"
            );
        }

        foreach ($callback as $key => $func) {
            if (
                !in_array($key, $keys) ||
                !is_callable($func)
            ) {
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

        if (count($groupBy) === 0) {
            return [];
        }

        $transverse = static::transverse($groupBy);

        if (
            !isset($transverse[$row_key]) ||
            !is_array($transverse[$row_key])
        ) {
            throw new InvalidArgumentException(
                "data type is different:{$row_key}"
            );
        }

        $rows = array_unique($transverse[$row_key]);

        if (
            !isset($transverse[$column_key]) ||
            !is_array($transverse[$column_key])
        ) {
            throw new InvalidArgumentException(
                "data type is different:{$column_key}"
            );
        }

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
        $result = [
            array_combine($title_columns, $title_columns)
        ];

        $result[0][$row_key] = $column_key;

        $c = current($dataset);

        $initial = [];

        foreach ($tables as $table) {
            $result[$table] = [];
            $initial[$table] = call_user_func(
                $callback[$table],
                []
            );
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
                    is_array($c) &&
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
    *   @param mixed[] $where  結合条件 [['column1-m',
    *                         'column2-n'], ・・・]
    *   @param string  $type   結合タイプ('left',
    *                         'inner')
    *   @param string  $suffix
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
            throw new InvalidArgumentException(
                "args type is different"
            );
        }

        $result = [];
        $col2_keys = [];

        foreach ($table1 as $row1) {
            if (!is_array($row1)) {
                throw new InvalidArgumentException(
                    "table1 is not 2D"
                );
            }

            $count = 0;

            foreach ($table2 as $row2) {
                if (!is_array($row2)) {
                    throw new InvalidArgumentException(
                        "table2 is not 2D"
                    );
                }

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
                    if (
                        !is_array($keys) ||
                        count($keys) !== 2 ||
                        (!is_int($keys[0]) && !is_string($keys[0])) ||
                        (!is_int($keys[1]) && !is_string($keys[1]))
                    ) {
                        throw new InvalidArgumentException(
                            "args type is different"
                        );
                    }
                    if (
                        !isset($row1[$keys[0]]) ||
                        !isset($row2[$keys[1]]) ||
                        $row1[$keys[0]] !== $row2[$keys[1]]
                    ) {
                        $join = false;
                    }
                }

                if ($join) {
                    array_push(
                        $result,
                        array_merge(
                            (array)$row1,
                            (array)array_combine(
                                $col2_keys,
                                (array)$row2
                            )
                        )
                    );
                    $count++;
                }
            }

            if (
                strtolower($type) === 'left' &&
                $count === 0
            ) {
                array_push(
                    $result,
                    array_merge(
                        (array)$row1,
                        array_fill_keys($col2_keys, null)
                    )
                );
            }
        }
        return $result;
    }
}
