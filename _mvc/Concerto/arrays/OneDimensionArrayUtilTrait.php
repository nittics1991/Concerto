<?php

/**
*   OneDimensionArrayUtil
*
*   @version 230112
*/

declare(strict_types=1);

namespace Concerto\arrays;

use InvalidArgumentException;
use Traversable;

trait OneDimensionArrayUtilTrait
{
    /**
    *   キーマージ(可変長引数)
    *
    *   @param mixed[] ...$args
    *   @return mixed[]
    *   @example mergeKey($array1, $array2, ...)
    *       $c = ['age' => 34, 'id' => 7;
    *       $d = ['aaa' => 'AAA', 'age' => 22, 'bbb' => 'BBB'];
    *       => ['age' => null, 'id' => null, 'aaa' => null, 'bbb' => null]
    */
    public static function mergeKey(
        ...$args
    ): array {
        return array_map(
            function ($val) {
                return $val = null;
            },
            array_flip(
                array_keys(
                    (array)call_user_func_array(
                        'array_merge',
                        func_get_args()
                    )
                )
            )
        );
    }

    /**
    *   キー保持マージ
    *
    *   @param mixed[] ...$args
    *   @return mixed[]
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
    public static function mergeKeepKey(
        ...$args
    ): array {
        $result = [];
        foreach ($args as $list) {
            foreach ($list as $key => $val) {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
    *   指定キーを持つ配列
    *
    *   @param mixed[] $array
    *   @param mixed[] $keys  抽出キー
    *   @return mixed[]
    */
    public static function extractKey(
        array $array,
        array $keys
    ): array {
        $result = [];
        foreach ($keys as $key) {
            if (!is_int($key) && !is_string($key)) {
                throw new InvalidArgumentException(
                    "keys must be int|string",
                );
            }

            $result[$key] = (array_key_exists($key, $array)) ?
                $array[$key] : null;
        }
        return $result;
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
    public static function some(
        array $array,
        callable $callback
    ): bool {
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
    public static function every(
        array $array,
        callable $callback
    ): bool {
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
    *   @param int $order ソート方法(array_multisort)
    *   @return mixed
    *   @throws InvalidArgumentException
    */
    public static function max(
        array $array,
        int $order = SORT_NUMERIC
    ): mixed {
        $sorted = $array;
        if (!array_multisort($sorted, $order)) {
            throw new InvalidArgumentException(
                "data type is different"
            );
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
    *   @param int $order ソート方法(array_multisort)
    *   @return mixed
    *   @throws InvalidArgumentException
    */
    public static function min(
        array $array,
        int $order = SORT_NUMERIC
    ): mixed {
        $sorted = $array;
        if (!array_multisort($sorted, $order)) {
            throw new InvalidArgumentException(
                "data type is different"
            );
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
    public static function first(
        array $array
    ): mixed {
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
    public static function last(
        array $array
    ): mixed {
        if (count($array) > 0) {
            return end($array);
        }
        return null;
    }
}
