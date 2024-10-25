<?php

namespace concerto\delegator;

use Concerto\delegator\StandardDelegatorTrait;

abstract class StandardDelegatorObject
{


  //delegatorの定義と変換方法の定義
protected array $delegator = [
    DelegateClass1::class => function (...$that) {
        return new DelegateClass1(
            $that->getId(),
        );
    },
    DelegateClass2::class => function (...$that) {
        return new DelegateClass2(
            $that->toString(),
        );
    },
  ];


  //methodの定義
  //implements method は 定義が必要

public function impMethod1(
    int $name1,
    string $name2,
): string {


    $result = call_user_func_array(
        DelegateClass1::class,
        [$name2, $name1, true],
    );

    if ($result === false) {
        $this->exceptionMethod(
            "aaaa"
        );
    }









    protected array $methods = [
    '__construct' => [
    DelegateClass1::class,
    'arguments' => [
      'param_name1' => 'p_name3',

    ],


    ];







    use StandardDelegatorTrait;

    /**
    *   @var string
    */
    protected static string $delegatorNamespace;

    /**
    *   @var object
    */
    protected object $delegator;

    /**
    *   {inherit}
    */
    protected static function delegatorNamespace(): string
    {
        return static::$delegatorNamespace;
    }

    /**
    *   {inherit}
    */
    abstract protected static function convertToOriginal(
        object $delegator
    ): object;

    /**
    *   {inherit}
    */
    abstract protected static function convertToDelegator(
        object $original
    ): object;

    /**
    *   {inherit}
    */
    public function __call(
        string $name,
        array $arguments
        //): mixed {
    ) {
        return static::convertAndExecuteAllArgumentsAndResult(
            [static::class, $name],
            $arguments
        );
    }

    /**
    *   {inherit}
    */
    public static function __callStatic(
        string $name,
        array $arguments
        //): mixed {
    ) {
        return static::convertAndExecuteAllArgumentsAndResult(
            [static::class, $name],
            $arguments
        );
    }

    /**
    *   {inherit}
    */
    public function __get(
        string $name
        //): mixed {
    ) {
        return $this->delegator->$name;
    }

    /**
    *   {inherit}
    */
    public function __set(
        string $name,
        array $arguments
    ): void {
        $this->delegator->$name = $arguments;
    }

    /**
    *   {inherit}
    */
    public function __isset(
        string $name
    ): bool {
        return isset($this->delegator->$name);
    }

    /**
    *   {inherit}
    */
    public function __unset(string $name): void
    {
        unset($this->delegator->$name);
    }
}
