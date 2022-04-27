<?php

/**
*   データコンテナValidatable
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\standard;

use Concerto\standard\DataContainer;
use Concerto\standard\Validatable;
use BadMethodCallException;

abstract class DataContainerValidatable extends DataContainer implements
    Validatable
{
    /**
    *   バリデートエラー情報
    *
    *   @var mixed[] ['column' => []]
    */
    protected $valid = [];

    /**
    *   {inherit}
    *
    */
    public static function __callStatic(
        string $name,
        array $arguments
    ): mixed {
        $obj = new static();
        $method = 'is' . mb_convert_case($name, MB_CASE_TITLE);

        if (
            mb_ereg_match('\AisValid', $method) &&
                method_exists($obj, $method)
        ) {
            return call_user_func_array([$obj, $method], $arguments);
        }
        return parent::__callStatic($name, $arguments);
    }

    /**
    *   バリデート
    *
    *   @return bool
    */
    public function isValid(): bool
    {
        $this->valid = [];
        $result = true;

        foreach (static::$schema as $prop) {
            $val = isset($this->data[$prop]) ? $this->data[$prop] : null;
            $result = $this->validCom($prop, $val) && $result;
            $result = $this->validCustom($prop, $val) && $result;
        }
        $result = $this->validRelation() && $result;

        return $result;
    }

    /**
    *   バリデート全変数共通処理
    *
    *   @param string $key 変数名
    *   @param mixed $val データ
    *   @return bool
    */
    protected function validCom($key, $val): bool
    {
        return true;
    }

    /**
    *   バリデートプロパティ間処理
    *
    *   @return bool
    */
    protected function validRelation(): bool
    {
        return true;
    }

    /**
    *   バリデート個別変数処理
    *
    *   @param string $key 変数名
    *   @param mixed $val データ
    *   @return bool
    */
    protected function validCustom($key, $val): bool
    {
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
            $this->valid[$key] = (array_key_exists($key, $this->valid)) ?
                $this->valid[$key] : [];
            $this->valid[$key] = array_merge($this->valid[$key], $result);
            return false;
        }
        $this->valid[$key][] = $result;
        return false;
    }

    /**
    *   バリデートエラーキー取得
    *
    *   @return array 値
    */
    public function getValidError()
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
    *   @return array
    */
    protected function getRecursiveError($target)
    {
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
    protected function isValidRecursive(
        array $targets,
        callable $callback
    ) {
        $result = true;
        foreach ($targets as $obj) {
            $result = (bool)$callback($obj) && $result;
        }
        return $result;
    }

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
