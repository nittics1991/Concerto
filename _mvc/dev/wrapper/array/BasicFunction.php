<?php

/**
*   BasicFunction
*
*   @version 210714
*/

declare(strict_types=1);

namespace Concerto\wrapper\array;

use RuntimeException;

class BasicFunction
{
    /**
    *   @val array [function_name]
    */
    protected array $functions = [];

    /**
    *   @val array [function_name=>argument_position]
    */
    protected array $not_first_array_argument = [];

    /**
    *   @val array [function_name]
    */
    protected array $has_related_value = [];

    /**
    *   @val mixed
    */
    protected mixed $related_value = null;

    /**
    *   functionList
    *
    *   @return array
    */
    public function functionList(): array
    {
        return $this->functions;
    }

    /**
    *   has
    *
    *   @param string $name
    *   @return bool
    */
    public function has(string $name): bool
    {
        return in_array(
            $name,
            $this->functions
        );
    }

    /**
    *   execute
    *
    *   @param array $dataset
    *   @param string $name
    *   @param array $arguments
    *   @return mixed
    */
    public function execute(
        array $dataset,
        string $name,
        array $arguments,
    ): mixed {
        $this->related_value = null;
        $this->checkFunction($name);

        $resolved_arguments = $this->resolveArgument(
            $dataset,
            $name,
            $arguments,
        );

        return $this->callFunction(
            $name,
            $resolved_arguments,
        );
    }

    /**
    *   relatedValue
    *
    *   @return mixed
    */
    public function relatedValue(): mixed
    {
        return $this->related_value;
    }

    /**
    *   checkFunction
    *
    *   @param string $name
    *   @return bool
    */
    protected function checkFunction(string $name): bool
    {
        if (!$this->has($name)) {
            throw new RuntimeException(
                "function is not defined:{$name}"
            );
        }
        return true;
    }

    /**
    *   callFunction
    *
    *   @param string $name
    *   @param array $arguments
    *   @return mixed
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

        if (in_array($name, $this->has_related_value)) {
            $this->related_value = $result;
        }
        return $result;
    }

    /**
    *   resolveArgumentPosition
    *
    *   @param string $name
    *   @return ?int
    */
    protected function resolveArgumentPosition(
        string $name,
    ): ?int {
        return array_key_exists(
            $name,
            $this->not_first_array_argument,
        ) ? $this->not_first_array_argument[$name] :
        null;
    }

    /**
    *   resolveArgument
    *
    *   @param array $dataset
    *   @param string $name
    *   @param array $arguments
    *   @return array
    */
    protected function resolveArgument(
        array $dataset,
        string $name,
        array $arguments,
    ): array {
        if (!is_null($this->resolveArgumentPosition($name))) {
            array_splice(
                $arguments,
                $this->not_first_array_argument[$name],
                0,
                [$dataset],
            );
              return $arguments;
        }

        array_unshift($arguments, $dataset);
        return $arguments;
    }
}
