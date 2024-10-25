<?php

/**
*   StrUtil
*
*   @version 230216
*/

declare(strict_types=1);

namespace candidate\util;

use BadMethodCallException;
use RuntimeException;

final class StrUtil
{
    /**
    *   @var string[]
    */
    private static array $error_judgements = [
        'mb_chr',
        'mb_convert_encoding',
        'mb_convert_variables',
        'mb_detect_encoding',
        'mb_ereg_replace_callback',
        'mb_ereg_replace',
        'mb_ereg_search_getregs',
        'mb_ereg_search_pos',
        'mb_ereg_search_regs',
        'mb_eregi_replace',
        'mb_get_info',
        'mb_ord',
        'mb_split',
    ];

    /**
    *   {inheritdDoc}
    */
    public static function __callStatic(
        string $name,
        array $arguments,
    ): mixed {
        $method = 'mb_' . static::snake($name);

        if (!function_exists($method)) {
            throw new BadMethodCallException(
                "not defined method:{$name}",
            );
        }

        $result = call_user_func_array(
            $method,
            $arguments,
        );

        if (
            in_array($method, static::$error_judgements) &&
            ($result === false || $result === null)
        ) {
            throw new RuntimeException(
                "execution result abnormality:{$name}",
            );
        }

        return $result;
    }

    /**
    *   snake
    *
    *   @param string $string
    *   @return string
    *   @example
    *       studyCaseString => study_case_string
    *       CamelCaseString => camel_case_string
    *       _snake_case_string => snake_case_string
    */
    public static function snake(
        string $string,
    ): string {
        $snaked = mb_ereg_replace(
            '([A-Z])',
            '_\\1',
            $string,
        );

        if (
            $snaked === false ||
            $snaked === null
        ) {
            throw new RuntimeException(
                "failure:{$string}",
            );
        }

        $lowerd = mb_strtolower($snaked);
        
        return $lowerd[0] === '_'?
            mb_substr($lowerd, 1):$lowerd;
    }
}
