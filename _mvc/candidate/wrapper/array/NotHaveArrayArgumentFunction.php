<?php

/**
*   NotHaveArrayArgumentFunction
*
*   @version 210714
*/

declare(strict_types=1);

namespace candidate\wrapper\array;

use candidate\wrapper\array\BasicFunction;

class NotHaveArrayArgumentFunction extends BasicFunction
{
    /**
    *   {inherit}
    */
    protected array $functions = [
        'array_fill',
        'range',
    ];

    /**
    *   {inherit}
    */
    public function resolveArgument(
        array $dataset,
        string $name,
        array $arguments,
    ): array {
        return $arguments;
    }
}
