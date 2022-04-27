<?php

declare(strict_types=1);

namespace test\Concerto;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;

trait PrivateTestTrait
{
    /**
    *   call method
    *
    *   @param object 対象オブジェクト
    *   @param string メソッド名
    *   @params array 引数
    */
    public function callPrivateMethod($class, $method, $args = [])
    {
        $refMethod = new ReflectionMethod($class, $method);
        $refMethod->setAccessible(true);
        return $refMethod->invokeArgs($class, $args);
    }

    /**
    *   get property
    *
    *   @param object | string 対象クラス
    *   @param string プロパティ名
    *   @return mixed
    */
    public function getPrivateProperty($object, $property)
    {
        return $this->doPropertyProcess(
            [$this, 'doGetProperty'],
            $object,
            $property
        );
    }

    /**
    *   set property
    *
    *   @param object | string 対象クラス
    *   @param string プロパティ名
    *   @param mixed 値
    */
    public function setPrivateProperty($object, $property, $value)
    {
        return $this->doPropertyProcess(
            [$this, 'doSetProperty'],
            $object,
            $property,
            $value
        );
    }

    /**
    *  プロパティ処理ルーチン
    *
    *   @param callable 処理本体
    *   @param object | string 対象クラス
    *   @param string プロパティ名
    *   @param null | mixed 設定値
    *   @param null | object 対象オブジェクト
    *   @return mixed
    *   @throws InvalidArguentException
    */
    protected function doPropertyProcess(
        $process,
        $class,
        $property,
        $value = null,
        $target = null
    ) {
        $refClass = new ReflectionClass($class);

        if ($refClass->hasProperty($property)) {
            $refProp = $refClass->getProperty($property);
            $refProp->setAccessible(true);

            $obj = (isset($target)) ? $target : $class;

            //do process
            return $process($refProp, $obj, $value);
        }

        if (($parent = $refClass->getParentClass()) === false) {
            throw new InvalidArgumentException("{$class} not have:{$property}");
        }
        return $this->doPropertyProcess(
            $process,
            $parent->getName(),
            $property,
            $value,
            $class
        );
    }

    /**
    *   doGetProperty
    *
    *   @param ReflectionProperty
    *   @param object
    *   @return mixed
    */
    protected function doGetProperty($refProp, $object, $dummy)
    {
        return $refProp->getValue($object);
    }

    /**
    *   doSetProperty
    *
    *   @param ReflectionProperty
    *   @param object
    *   @return mixed
    */
    protected function doSetProperty($refProp, $object, $value)
    {
        if ($refProp->isStatic()) {
            return $refProp->setValue($value);
        }
        return $refProp->setValue($object, $value);
    }
}
