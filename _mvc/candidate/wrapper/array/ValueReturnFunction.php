<?php

/**
*   ValueReturnFunction
*
*   @version 210714
*/

declare(strict_types=1);

namespace candidate\wrapper\array;

use candidate\wrapper\array\BasicFunction;

class ValueReturnFunction extends BasicFunction
{
    /**
    *   @inheritDoc
    */
    protected array $functions = [
        'array_key_exists',
        'array_key_first',
        'array_key_last',
        'array_map',
        'array_product',
        'array_rand',
        'array_reduce',
        'array_search',
        'array_sum',
        'count',
        'in_array',
    ];

    /**
    *   @inheritDoc
    */
    protected array $not_first_array_argument = [
        'array_key_exists' => 1,
        'array_map' => 1,
        'array_search' => 1,
        'in_array' => 1,
    ];
}
