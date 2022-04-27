<?php

ini_set('error_reporting', E_ALL);
ini_set('display_error', '1');

//ライブラリ側

interface LibInterface
{
    public function nonInjected(string $str);
    public function injected(LibInterface $obj);
    //convertDelegatorで使う
    public function getLibId();
} 

class LibClass implements LibInterface
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function nonInjected(string $str)
    {
        var_dump(__METHOD__, $str);
        return $str;
    }
    
    public function injected(LibInterface $obj)
    {
        var_dump(__METHOD__, $obj->id);
        return $this->id;
    }
    
    public function extended($str)
    {
        var_dump(__METHOD__, $str);
        return $str;
    }
    
    //convertDelegatorで使う
    public function getLibId()
    {
        var_dump(__METHOD__);
        return $this->id;
    }
}

//プロジェクト側

interface MyInterface
{
    public function nonInjected(string $str);
    public function injected(MyInterface $obj);
} 

class MyClass implements MyInterface
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function nonInjected(string $str)
    {
        var_dump(__METHOD__, $str);
        return $str;
    }
    
    public function injected(MyInterface $obj)
    {
        var_dump(__METHOD__, $obj->id);
        return $this->id;
    }
    
    //convertOriginalで使う
    public function getMyId()
    {
        var_dump(__METHOD__);
        return $this->id;
    }
}

//ライブラリ側

echo "--------------------------ライブラリ側start\n";

$idLib1 = 'ライブラリ1';
$LibClass1 = new LibClass($idLib1);
var_dump($LibClass1->getLibId());

$idLib2 = 'ライブラリ2';
$LibClass2 = new LibClass($idLib2);
var_dump($LibClass2->getLibId());

$str = '継承した関数';
$LibClass1->extended($str);

$LibClass1->nonInjected("nonInjected={$idLib1}");
$LibClass1->injected($LibClass2);

//プロジェクト側

echo "--------------------------プロジェクト側start\n";

$idMy1 = 'プロジェクト1';
$MyClass1 = new MyClass($idMy1);
var_dump($MyClass1->getMyId());

$idMy2 = 'プロジェクト2';
$MyClass2 = new MyClass($idMy2);
var_dump($MyClass2->getMyId());

$MyClass1->nonInjected("nonInjected={$idMy1}");
$MyClass1->injected($MyClass2);



////////////////////////////////////////////


//ブリッジ

trait BridgeTrait
{
    /**
    *   delegatorNamespace
    *
    *   @return string
    */
    abstract protected static function delegatorNamespace():string;
    
    /**
    *   convertToOriginal
    *
    *   @param object $original
    *   @return object
    */
    abstract protected static function convertToOriginal(
        object $delegator
    ):object;
    
    /**
    *   convertToDelegator
    *
    *   @param object $original
    *   @return object
    */
    abstract protected static function convertToDelegator(
        object $original
    ):object;
    
    /**
    *   isOriginalObject
    *
    *   @param mixed $target
    *   @return bool
    */
    //protected static function isOriginalObject(mixed $target):bool
    protected static function isOriginalObject($target):bool
    {
        $originalNamespace = static::class;
        return $target instanceof $originalNamespace;
    }
    
    /**
    *   isDelegatorObject
    *
    *   @param mixed $target
    *   @return bool
    */
    //protected static function isDelegatorObject(mixed $target):bool
    protected static function isDelegatorObject($target):bool
    {
        $delegatorNamespace = static::delegatorNamespace();
        return $target instanceof $delegatorNamespace;
    }
    
    /**
    *   convertAllArgumentsUgingDelegator
    *
    *   @param array $arguments
    *   @return array
    */
    protected static function convertAllArgumentsUgingDelegator(
        array $arguments
    ):array {
        $delegated = [];
        foreach($arguments as $argument) {
            $delegated[] = static::isOriginalObject($argument)?
                static::convertToDelegator($argument):
                $argument;
        }
        
        return $delegated;
    }
    
    /**
    *   convertAndExecuteAllArgumentsAndResult
    *
    *   @param callable $callback
    *   @param array $arguments
    *   @return mixed
    */
    protected static function convertAndExecuteAllArgumentsAndResult(
        callable $callback,
        array $arguments
    //): mixed {
    ) {
        $converted = static::convertAllArgumentsUgingDelegator(
            $arguments,
        );
        
        $result = call_user_func_array(
            $callback,
            $converted,
        );
        
        return static::isOriginalObject($result)?
            static::convertToDelegator($result):
            $result;
    }
}


abstract class StandardBridgeObject
{
    use BridgeTrait;
    
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
    protected static function delegatorNamespace():string
    {
        return static::$delegatorNamespace;
    }
    
    /**
    *   {inherit}
    */
    abstract protected static function convertToOriginal(
        object $delegator
    ):object;
    
    /**
    *   {inherit}
    */
    abstract protected static function convertToDelegator(
        object $original
    ):object;
    
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
    public function __unset(string $name):void {
        unset($this->delegator->$name);
    }
}





//abstractだとnamespaceをstaticで取得する為、危険
class BridgeClass extends StandardBridgeObject implements MyInterface
{
    /**
    *   @var string 
    */
    protected static string $delegatorNamespace = LibClass::class;
    
    /**
    *   @var string //MyInterface
    */
    protected string $id;
    
    /**
    *   __construct
    *
    *   @param array $arguments
    *   @return object
    */
    public function __construct(
        string $id
    ) {
        $delegatorNamespace = static::delegatorNamespace();
        $this->delegator = new $delegatorNamespace(
            $id
        );
    }
    
    /**
    *   {inherit}
    */
    protected static function convertToOriginal(
        object $delegator
    ):object{
        return call_user_func_array(
            [
                static::class,
                '__construct',
            ],
            $delegator->getLibId(),
        );
    }
    
    /**
    *   {inherit}
    */
    protected static function convertToDelegator(
        object $original
    ):object{
        $delegatorNamespace = static::delegatorNamespace();
        
        return new $delegatorNamespace(
            $original->getMyId()
        );
    }
    
    
    //MyInterface method
    
    /**
    *   {inherit}
    */
    public function nonInjected(string $str)
    {
        return static::convertAndExecuteAllArgumentsAndResult(
            [
                $this->delegator,
                'nonInjected',
            ],
            [$str]
        );
    }
    
    /**
    *   {inherit}
    */
    public function injected(MyInterface $obj)
    {
        return static::convertAndExecuteAllArgumentsAndResult(
            [
                $this->delegator,
                'injected',
            ],
            [$obj]
        );
    }
    
    
    
    //convertToOriginalで使うconstruct引数取得用
    public function getMyId()
    {
        return $this->delegator->getLibId();
    }
    
    
    
    
    
    //bridgeオリジナルmethod
    public function bridge($str)
    {
        var_dump($str);
        return $str;
    }
}


/////////////////////////////

//こんな感じで実行したい

echo "--------------------------ブリッジstart\n";


$idBridge1 = 'ブリッジ1';
$BridgeClass1 = new BridgeClass($idBridge1);
var_dump($BridgeClass1->id);

$BridgeClass1->bridge('bridgeオリジナルmethod');


echo "--------------------------\n";

$idBridge2 = 'ブリッジ2';
$BridgeClass2 = new BridgeClass($idBridge2);
var_dump($BridgeClass2->id);

echo "--------------------------injected start\n";

$BridgeClass1->nonInjected("nonInjected={$idBridge1}");
$BridgeClass1->injected($BridgeClass2);

echo "--------------------------END\n";
