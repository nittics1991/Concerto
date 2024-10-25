<?php

/**
*   Path
*
*   @version 170509
*/

declare(strict_types=1);

namespace candidate\filesystem;

class Path
{
    /**
    *   resolve
    *
    *   @param string $path
    *   @return string
    */
    public static function resolve(string $path = __DIR__): string
    {
        $splited = self::split($path);
        $resolved = [];

        foreach ($splited as $val) {
            if ($val == '..') {
                array_pop($resolved);
            } elseif ($val == '.') {
                //nop
            } else {
                $resolved[] = $val;
            }
        }
        return self::join($resolved);
    }

    /**
    *   split
    *
    *   @param string $path
    *   @return string[]
    */
    public static function split(string $path)
    {
        return (array)mb_split('[\\\\/]', $path);
    }

    /**
    *   join
    *
    *   @param string[] $pathset
    *   @return string
    */
    public static function join(array $pathset): string
    {
        return join(DIRECTORY_SEPARATOR, $pathset);
    }
}
