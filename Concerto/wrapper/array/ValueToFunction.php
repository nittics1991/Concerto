<?php

/**
*   ValueToFunction
*
*   @version 210716
*/

declare(strict_types=1);

namespace Concerto\wrapper\array;

use Concerto\wrapper\array\BasicFunction;

class ValueToFunction extends BasicFunction
{
    /**
    *   {inherit}
    */
    protected array $functions = [
        'array_change_key_case',
        'array_chunk',
        'array_column',
        'array_count_values',
        'array_diff',
        'array_diff_assoc',
        'array_diff_key',
        'array_diff_uassoc',
        'array_diff_ukey',
        'array_fill_keys',
        'array_filter',
        'array_flip',
        'array_intersect',
        'array_intersect_assoc',
        'array_intersect_key',
        'array_intersect_uassoc',
        'array_intersect_ukey',
        'array_keys',
        'array_merge',
        'array_merge_recursive',
        'array_pad',
        'array_replace',
        'array_replace_recursive',
        'array_reverse',
        'array_slice',
        'array_udiff',
        'array_udiff_assoc',
        'array_udiff_uassoc',
        'array_uintersect',
        'array_uintersect_assoc',
        'array_uintersect_uassoc',
        'array_unique',
        'array_values',
    ];
}
