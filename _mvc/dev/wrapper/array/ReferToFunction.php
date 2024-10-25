<?php

/**
*   ReferToFunction
*
*   @version 210716
*/

declare(strict_types=1);

namespace Concerto\wrapper\array;

use RuntimeException;
use Concerto\wrapper\array\BasicFunction;

class ReferToFunction extends BasicFunction
{
    /**
    *   {inherit}
    */
    protected array $functions = [
        'array_multisort',
        'array_pop',
        'array_push',
        'array_shift',
        'array_splice',
        'array_unshift',
        'array_walk',
        'array_walk_recursive',
        'arsort',
        'asort',
        'krsort',
        'ksort',
        'natcasesort',
        'natsort',
        'rsort',
        'shuffle',
        'sort',
        'uasort',
        'uksort',
        'usort',
    ];

    /**
    *   {inherit}
    */
    protected array $has_related_value = [
        'array_splice',
        'array_pop',
        'array_shift',
    ];

    /**
    *   {inherit}
    */
    protected function callFunction(
        string $name,
        array $arguments,
    ): mixed {
        if (!is_callable($name)) {
            throw new RuntimeException(
                "method is not callable :{$name}"
            );
        }

        $result = call_user_func_array(
            $name,
            $arguments,
        );

        if ($result === false) {
            throw new RuntimeException(
                "execution failure :{$name}"
            );
        }

        reset($arguments);
        $return = current($arguments);

        if (in_array($name, $this->has_related_value)) {
            $this->related_value = $result;
            return $return;
        }

        if ($result === false) {
            throw new RuntimeException(
                "execution failure :{$name}"
            );
        }
        return $return;
    }

    /**
    *   {inherit}
    */
    protected function resolveArgument(
        array $dataset,
        string $name,
        array $arguments,
    ): array {
        return array_merge(
            [&$dataset],
            $arguments,
        );
    }
}
