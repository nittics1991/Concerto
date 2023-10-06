<?php

/**
 *   ArrayDot
 *
 * @version 221221
 */

declare(strict_types=1);

namespace Concerto\arrays;

final class ArrayDot
{
    /**
    *   set
    *
    *   @param mixed[] $array
    *   @param string $dot
    *   @param mixed $val
    *   @return mixed[]
    */
    public static function set(
        array $array,
        string $dot,
        mixed $val
    ): array {
        $splits = explode('.', $dot);
        $reversed = array_reverse($splits);

        $dimension = (array)array_reduce(
            $reversed,
            function ($carry, $item) {
                return [$item => $carry];
            },
            $val
        );
        return (array)array_replace_recursive(
            $array,
            $dimension
        );
    }

    /**
    *   get
    *
    *   @param  mixed[] $array
    *   @param  string $dot
    *   @param  mixed $default
    *   @return mixed
    */
    public static function get(
        array $array,
        string $dot,
        mixed $default = null
    ): mixed {
        return array_reduce(
            explode('.', $dot),
            function ($carry, $item) use ($default) {
                return isset($carry[$item]) ?
                    $carry[$item] : $default;
            },
            $array
        );
    }

    /**
    *   has
    *
    *   @param mixed[] $array
    *   @param string $dot
    *   @return bool
    */
    public static function has(
        array $array,
        string $dot
    ): bool {
        $get = self::get($array, $dot);
        return isset($get);
    }

    /**
    *   remove
    *
    *   @param mixed[] $array
    *   @param string $dot
    *   @return mixed[]
    */
    public static function remove(
        array $array,
        string $dot
    ): array {
        $exploded = explode('.', $dot);

        if (empty($exploded[0])) {
            return $array;
        }

        $target = &$array;
        $lastName = array_pop($exploded);

        foreach ($exploded as $name) {
            if (!isset($target[$name])) {
                return $array;
            }
            $target = &$target[$name];
        }
        unset($target[$lastName]);
        return $array;
    }
}
