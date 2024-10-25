<?php

/**
*   DataContainerValidatable
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\standard;

use BadMethodCallException;
use Concerto\standard\{
    DataContainer,
    Validatable
};

/**
*   @template TValue
*   @extends DataContainer<TValue>
*/
abstract class DataContainerValidatable extends DataContainer implements
    Validatable
{
    /**
    *   @var mixed[] ['column' => []]
    */
    protected array $valid = [];

    /**
    *   @inheritDoc
    *
    *   @param string $name
    *   @param mixed[] $arguments
    */
    public static function __callStatic(
        string $name,
        array $arguments
    ): mixed {
        $obj = new static();
        $method = 'is' .
            mb_convert_case($name, MB_CASE_TITLE);

        if (
            mb_ereg_match('\AisValid', $method) &&
                method_exists($obj, $method)
        ) {
            return call_user_func_array(
                [$obj, $method],
                $arguments
            );
        }
        throw new BadMethodCallException(
            "not dedined:{$name}",
        );
    }

    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid(): bool
    {
        $this->valid = [];
        $result = true;

        foreach (static::$schema as $prop) {
            $val = isset($this->data[$prop]) ?
                $this->data[$prop] : null;

            $result = $this->validCom($prop, $val) &&
                $result;

            $result = $this->validCustom($prop, $val) &&
                $result;
        }
        $result = $this->validRelation() && $result;

        return $result;
    }

    /**
    *   validCom
    *
    *   @param string $key
    *   @param mixed $val
    *   @return bool
    */
    protected function validCom(
        string $key,
        mixed $val
    ): bool {
        return true;
    }

    /**
    *   validRelation
    *
    *   @return bool
    */
    protected function validRelation(): bool
    {
        return true;
    }

    /**
    *   validCustom
    *
    *   @param string $key
    *   @param mixed $val
    *   @return bool
    */
    protected function validCustom(
        string $key,
        mixed $val
    ): bool {
        $function = 'isValid' . ucfirst($key);
        if (!method_exists(get_called_class(), $function)) {
            return true;
        }

        $result = $this->$function($val);

        if ($result === true) {
            return true;
        }

        if ($result === false) {
            $this->valid[$key][] = '';
            return false;
        }

        if (is_array($result)) {
            $this->valid[$key] =
                array_key_exists($key, $this->valid) ?
                $this->valid[$key] : [];

            $this->valid[$key] = array_merge(
                $this->valid[$key],
                $result
            );

            return false;
        }
        $this->valid[$key][] = $result;
        return false;
    }

    /**
    *   getValidError
    *
    *   @return mixed[]
    */
    public function getValidError(): array
    {
        return $this->valid = array_merge(
            $this->valid,
            $this->getRecursiveError($this)
        );
    }

    /**
    *   getRecursiveError
    *
    *   @param iterable $target
    *   @return mixed[]
    */
    protected function getRecursiveError(
        iterable $target
    ): array {
        $valid = [];

        foreach ($target as $key => $val) {
            if (
                is_object($val) &&
                    $val instanceof DataContainerValidatable
            ) {
                $result = $val->getValidError();
                if (!empty($result)) {
                    $valid[$key] = $result;
                }
            } elseif (is_iterable($val)) {
                $result = $this->getRecursiveError($val);
                if (!empty($result)) {
                    $valid[$key] = $result;
                }
            }
        }
        return $valid;
    }

    /**
    *   isValidRecursive
    *
    *   @param array $targets
    *   @param callable $callback
    *   @return bool
    */
    // protected function isValidRecursive(
        // array $targets,
        // $callback
    // ): bool {
        // $result = true;
        // foreach ($targets as $obj) {
            // $result = (bool)$callback($obj) && $result;
        // }
        // return $result;
    // }

    /**
    *   個別パラメータバリデート
    *
    *   @param mixed 判定値
    *   @return mixed true/false or array
    *
    *   @example public function isValid{ColumnName}($val)
    *       ColumnName 列名
    *
    */
}
