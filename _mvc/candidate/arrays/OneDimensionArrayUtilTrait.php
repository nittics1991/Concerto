<?php

/**
*   OneDimensionArrayUtil
*
*   @version 210903
*/

declare(strict_types=1);

namespace candidate\arrays;

use InvalidArgumentException;
use Traversable;

trait OneDimensionArrayUtilTrait
{
    /**
    *   回転
    *
    *   @param mixed[] $array
    *   @param int $count
    *   @return mixed[]
    */
    public static function rotate(array $array, int $count = 1): array
    {
        return array_merge(
            array_slice($array, $count),
            array_slice($array, 0, $count)
        );
    }

    /**
    *   キーマージ(可変長引数)
    *
    *   @param mixed[] ...$args
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example mergeKey($array1, $array2, ...)
    *       $c = ['age' => 34, 'id' => 7;
    *       $d = ['aaa' => 'AAA', 'age' => 22, 'bbb' => 'BBB'];
    *       => ['age' => null, 'id' => null, 'aaa' => null, 'bbb' => null]
    */
    public static function mergeKey(...$args): array
    {
        foreach ($args as $val) {
            if (!is_array($val)) {
                throw new InvalidArgumentException("data type is different");
            }
        }

        return array_map(
            function ($val) {
                return $val = null;
            },
            array_flip(
                array_keys(
                    call_user_func_array(
                        'array_merge',
                        func_get_args()
                    )
                )
            )
        );
    }

    /**
    *   キーマージ(配列引数)
    *
    *   @param mixed[] $array [[..], [..], ...]
    *   @return mixed[]
    *   @throws InvalidArgumentException
    */
    public static function mergeKeyArray(array $array = []): array
    {
        return call_user_func_array(
            [__CLASS__, 'mergeKey'],
            $array
        );
    }

    /**
    *   キー保持マージ
    *
    *   @param mixed[] ...$args
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example mergeKeepKey($x, $y)
    *       $x = ['AA' => 'aa', 'BB' => 'bb', '012' => 012, 012 => '8進',
    *                '345' => 345, 014 => '8進2']
    *       $y = [0, 1, '345' => 'new', 12 => 'ZZZ', '012' => 'max', 13, 14])
    *       ==> ['AA' => 'aa', 'BB' => 'bb', '012' => 'max',
    *               10 => '8進', 345 => 'new',
    *           12 => 'ZZZ', 0 => 0, 1 => 1, 346 => 13, 347 => 14]
    *
    *       $xのキーが012は8進数と判断し、10進数へ変換される
    *       $yのキー'345'は10進数へ変換される
    *       $yのキー無し13, 14はキー最大が345なので、キーが再計算される
    */
    public static function mergeKeepKey(...$args): array
    {
        foreach ($args as $key => $val) {
            if (!is_array($val)) {
                throw new InvalidArgumentException("array only:{$key}");
            }
        }

        $result = [];
        foreach ($args as $list) {
            foreach ($list as $key => $val) {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
    *   置換対象値の構造を変えずにarray_replace
    *
    *   @param mixed[] ...$args 置換対象値, 上書き値, ・・・
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example
    *       $x = ['a' => 1, 'b' => 2, 'c' => 3]
    *       $y = ['x'  =>11, 'a' => 12, 'Y' => 13, 'c' => 14]
    *       $z = ['c' => 24, 'y' => 25]
    *       => ['a' => 12, 'b' => 2, 'c' => 24]
    */
    public static function replaceInitParam(...$args): array
    {
        if (count($args) < 2) {
            throw new InvalidArgumentException("required 2 or more");
        }
        $init = current($args);

        $joined = array_reduce(
            $args,
            function ($carry, $item) {
                return $item + $carry;
            },
            []
        );

        return self::extractKey(
            $joined,
            array_keys($init)
        );
    }

    /**
    *   第1引数の除外値を除外して第2引数以降をarray_replace
    *
    *   @param mixed[] ...$args 除外値,対象配列(2つ以上)
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example replaceWithout([0], [1,2,0,4,5], [11,0,13,14])
    *       =>[11,2,13,14,5]
    */
    public static function replaceWithout(...$args): array
    {
        if (count($args) < 3) {
            throw new InvalidArgumentException("required 3 or more");
        }

        foreach ($args as $key => $val) {
            if (!is_array($val)) {
                throw new InvalidArgumentException("array only:{$key}");
            }
        }

        $exclude = array_shift($args);
        $base = array_shift($args);

        $func = function ($ex, $src, $dest) {
            $result = [];

            foreach ($src as $key => $val) {
                if (array_key_exists($key, $dest)) {
                    $result[$key] = (in_array($dest[$key], $ex)) ?
                        $val : $dest[$key];
                } else {
                    $result[$key] = $val;
                }
                unset($dest[$key]);
            }
            return array_merge($result, $dest);
        };

        foreach ($args as $ar) {
            $base = $func($exclude, $base, $ar);
        }
        return (array)$base;
    }

    /**
    *   キー位置
    *
    *   @param mixed[] $array
    *   @param mixed $needle 検索キー
    *   @return int|false
    */
    public static function positionKey(array $array, $needle)
    {
        $keys = array_keys($array);
        for (
            $i = 0, $length = count($keys);
            $i < $length;
            $i++
        ) {
            if ($keys[$i] === $needle) {
                return $i;
            }
        }
        return false;
    }

    /**
    *   指定キーを持つ配列
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys 抽出キー
    *   @return mixed[]
    */
    public static function extractKey(array $array, array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = (array_key_exists($key, $array)) ?
                $array[$key] : null;
        }
        return $result;
    }

    /**
    *   指定キーを除いた配列
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys 除外キー
    *   @return mixed[]
    */
    public static function unextractKey(array $array, array $keys): array
    {
        $result = [];
        foreach (array_keys($array) as $key) {
            if (!in_array($key, $keys)) {
                $result[$key] = $array[$key];
            }
        }
        return $result;
    }

    /**
    *   キー変換
    *
    *   @param mixed[] $array
    *   @param mixed[] $srcKey 変換元キー名
    *   @param mixed[] $destKey 変換後キー名
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example keyRemap(['id' => 'a', 'name' => 'b', 'c'],
    *                                       ['id', 0], ['ID', 'index'])
    *           ==> ['ID' => 'a', 'index' => 'c']
    */
    public static function keyRemap(
        array $array,
        array $srcKey,
        array $destKey
    ): array {
        if (count($srcKey) != count($destKey)) {
            throw new InvalidArgumentException("unmatch key count");
        }

        $result = [];

        for (
            $i = 0, $length = count($srcKey);
            $i < $length;
            $i++
        ) {
            $src = $srcKey[$i];
            $dest = $destKey[$i];
            $result[$dest] = (isset($array[$src])) ? $array[$src] : null;
        }
        return $result;
    }

    /**
    *   キー変換(指定以外のキーはそのまま)
    *
    *   @param mixed[] $array
    *   @param mixed[] $srcKey 変換元キー名
    *   @param mixed[] $destKey 変換後キー名
    *   @return mixed[]
    *   @throws InvalidArgumentException
    *   @example keyRemap(['id' => 'a', 'name' => 'b', 'c'],
    *                                       ['id', 0], ['ID', 'index'])
    *           ==> ['ID' => 'a', 'name' => 'b', 'index' => 'c']
    */
    public static function keyPartiallyRemap(
        array $array,
        array $srcKey,
        array $destKey
    ): array {
        if (count($srcKey) != count($destKey)) {
            throw new InvalidArgumentException("unmatch key count");
        }

        $result = [];

        foreach ($array as $key => $val) {
            if (($pos = array_search($key, $srcKey)) !== false) {
                $result[$destKey[$pos]] = $val;
            } else {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
    *   指定値を除いた配列
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys 抽出値
    *   @param bool $keep true:キー保持 fase:キー削除
    *   @return mixed[]
    */
    public static function without(
        array $array,
        array $keys,
        $keep = true
    ): array {
        $result = [];
        foreach ($array as $key => $val) {
            if (!in_array($val, $keys)) {
                if ($keep) {
                    $result[$key] = $array[$key];
                } else {
                    $result[] = $array[$key];
                }
            }
        }
        return $result;
    }

    /**
    *   配列で指定した値をキーとした(多次元)配列を組み立てる(初期化)
    *
    *   @param mixed ...$args args[0]=>初期値, args[n]=>array
    *   @return mixed[]
    *   @exapmle initArray('z', ['A', 'B', 'C'], [1, 2])
    *       [A][1],[A][2],[B][1],[B][2],[C][1],[C][2] => ALL('z')
    */
    public static function initArray(...$args): array
    {
        $args = func_get_args();
        $initial = array_shift($args);
        krsort($args);

        return array_reduce(
            $args,
            function ($carry, $val) {
                return array_combine($val, array_fill(0, count($val), $carry));
            },
            $initial
        );
    }

    /**
    *   次元判定
    *
    *   @param mixed  $array
    *   @param int $dimension 判定次元
    *   @param int $current 現在次元
    *   @return bool
    *   @throws InvalidArgumentException
    */
    public static function isDimension(
        $array,
        int $dimension = 2,
        int $current = 1
    ): bool {
        if ($dimension < 1 || $current < 1) {
            throw new InvalidArgumentException("setting value is different");
        }

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
    *   some
    *
    *   @param mixed[] $array
    *   @param callable $callback
    *   @return bool
    *   @example some([1, 'A', 3], function($key, $val) {return is_int($val);})
    *       ==> true
    */
    public static function some(array $array, callable $callback): bool
    {
        foreach ($array as $key => $val) {
            if ($callback($key, $val) === true) {
                return true;
            }
        }
        return false;
    }

    /**
    *   every
    *
    *   @param mixed[] $array
    *   @param callable $callback
    *   @return bool
    *   @example every([1, 'A', 3], function($key, $val){return is_int($val);})
    *       ==> false
    */
    public static function every(array $array, callable $callback): bool
    {
        foreach ($array as $key => $val) {
            if (!$callback($key, $val)) {
                return false;
            }
        }
        return true;
    }

    /**
    *   最大値(ソート指定)
    *
    *   @param mixed[] $array データ
    *   @param mixed $order ソート方法(array_multisort)
    *   @return mixed
    *   @throws InvalidArgumentException
    */
    public static function max(array $array, $order = SORT_NUMERIC)
    {
        $sorted = $array;
        if (!array_multisort($sorted, $order)) {
            throw new InvalidArgumentException("data type is different");
        }

        if (count($sorted) > 0) {
            return $sorted[count($sorted) - 1];
        }
        return null;
    }

    /**
    *   最小値(ソート指定)
    *
    *   @param mixed[] $array データ
    *   @param mixed $order ソート方法(array_multisort)
    *   @return mixed
    *   @throws InvalidArgumentException
    */
    public static function min(array $array, $order = SORT_NUMERIC)
    {
        $sorted = $array;
        if (!array_multisort($sorted, $order)) {
            throw new InvalidArgumentException("data type is different");
        }

        if (count($sorted) > 0) {
            return $sorted[0];
        }
        return null;
    }

    /**
    *   先頭値
    *
    *   @param mixed[] $array データ
    *   @return mixed
    */
    public static function first(array $array)
    {
        if (count($array) > 0) {
            return reset($array);
        }
        return null;
    }

    /**
    *   最後値
    *
    *   @param mixed[] $array データ
    *   @return mixed
    */
    public static function last(array $array)
    {
        if (count($array) > 0) {
            return end($array);
        }
        return null;
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
            !is_array($x) &&
            !is_array($y) &&
            !is_object($x) &&
            !is_object($y)
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
}
