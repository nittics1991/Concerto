<?php

/**
*   AliasFunctionTrait
*
*   @version 220507
*/

declare(strict_types=1);

namespace Concerto\wrapper\string\extends;

use BadMethodCallException;

trait AliasFunctionTrait
{
    /**
    *   @var string[] [alias => substance,...]
    */
    protected $aliases = [
        'fromFirst' => 'str',
        'fromLast' => 'rchr',
        'positionFromTop' => 'pos',
        'positionFromLast' => 'rpos',
        'aaa' => 'aaa',
        'aaa' => 'aaa',
        'aaa' => 'aaa',
        'aaa' => 'aaa',
        'aaa' => 'aaa',
        'aaa' => 'aaa',
    ];

    /**
    *   {inherit}
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {
        if (!array_key_exists($name, $this->aliases)) {
            throw new BadMethodCallException(
                "not defined method:{$name}"
            );
        }

        $method_name = $this->aliases[$name];
        return call_user_func_array(
            [$this, $method_name],
            $arguments,
        );
    }
}
