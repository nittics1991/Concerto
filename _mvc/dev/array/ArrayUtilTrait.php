<?php

/**
*   aaa
*
*   @version 220224
*/

declare(strict_types=1);

namespace dev\array;

class ArrayUtilTrait
{
//////array対象

    /*
    *   指定キーに揃える
    *
    *   @param array $target
    *   @param array $keys [key1,...]
    *   @param array $complement
    *       [keyB => value2, keyX => callable($target),...]
    *       未指定:null
    *   @param bool $rest_removed
    *   @return array
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
            ):
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
    *   各値を配列で包む    //必要か?
    *
    *   @param array $target
    *   @return array
    */
    public static function wrap(
        array $target,
    ): array {
        return array_map(
            fn($value) => [$value],
            $target,
        );
    }

    /*
    *   平坦化
    *
    *   @param array $target
    *   @param int $nth
    *   @return array
    */
    public static function flatten(
        array $target,
        int $nth = 1,
    ): array {
    }





    /*
    *   最大次元数
    *
    *   @param array $target
    *   @return array
    */
    public static function dimension(
        array $target,
        int $depth,
    ): int {
        $max_dimension = 0;

        //depthにどう移動する?


        foreach ($target as $key => $value) {
            $max_dimension = max(
                $max_dimension,
                count($value),
            );
        }
        
        
        
        return 0;
        
    }


    /*
    *   
    *
    *   @param array $target
    *   @return array
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
                [$value]: array_values($value);
            
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





    /*
    *   指定文字列で各文字列を分割・平坦化
    *
    *   @param array $target
    *   @param string|callable $separator fn(string $value):array
    *   @return array
    */
    public static function expandElement2(
        array $target,
        string|callable $separator = PHP_EOL,
    ): array {
        $splited = [];
        $column_names = [];
        $max_dimension = 0;

        foreach ($target as $key => $value) {
            $splited[$key] = !is_string($value) ?
                [$value] :
                (
                    is_callable($separator) ?
                        call_user_func($separator, $value) :
                        mb_split($separator, $value)
                );

            if (!in_array($key, $column_names)) {
                $column_names[] = $key;
            }

            $dimension = count($splited[$key]);
            $max_dimension = max($max_dimension, $dimension);
        }

        $aligned = [];

        foreach ($splited as $key => $columns) {
            if (count($columns) === $max_dimension) {
                $aligned[$key] = $columns;
            } else {
                $inners = [];
                for ($i = 0; $i < $max_dimension; $i++) {
                    $inners[] = $columns[$i] ?? null;
                }
                $aligned[$key] = $inners;
            }
        }

        $result = [];

        for ($i = 0; $i < $max_dimension; $i++) {
            foreach ($column_names as $column) {
                $result[$i][$column] = $aligned[$column][$i];
            }
        }
        return $result;
    }


    //dimension(mixed $target):int

    //flatten(array $target, int $n = 1)


///////table対象

    //isTable(mixed $target):bool

    //selectBy(array $target, array $keys = []):array

    //where:array = filter

    //orderBy:array (like) array_multisort

    //join(array $src, array $dest, callable $filter):array

    //leftJoin(array $src, array $dest, callable $filter):array
}