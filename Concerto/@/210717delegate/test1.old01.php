<?php

//ライブラリ側

interface LibInterface
{
    public function injected(LibInterface $obj);
} 

class LibClass implements LibInterface
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function injected(LibInterface $obj)
    {
        var_dump($obj->id);
        return $this->id;
    }
    
    public function extended($str)
    {
        var_dump($str);
        return $str;
    }
}

//プロジェクト側

interface MyInterface
{
    public function injected(MyInterface $obj);
} 

class MyClass implements MyInterface
{
    public $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function injected(MyInterface $obj)
    {
        var_dump($obj->id);
        return $this->id;
    }
}

//ライブラリ側

$idLib1 = 'ライブラリ1';
$LibClass1 = new LibClass($idLib1);
var_dump($LibClass1->id);

$idLib2 = 'ライブラリ2';
$LibClass2 = new LibClass($idLib2);
var_dump($LibClass2->id);

$str = '継承した関数';
$LibClass1->extended($str);

$LibClass1->injected($LibClass2);



//プロジェクト側

$idMy1 = 'プロジェクト1';
$MyClass1 = new MyClass($idMy1);
var_dump($MyClass1->id);

$idMy2 = 'プロジェクト2';
$MyClass2 = new MyClass($idMy2);
var_dump($MyClass2->id);

$MyClass1->injected($MyClass2);

//ブリッジ

trait BridgeTrait
{
    
    //__call()でcall_user_func_array
    private string $originalClass;
    private string $delegateClass;
    
    abstract private function convertClass($originalClass, $arguments);
        //どうやって実装?
    
    
    
    
    public function callBridge($name, $arguments)
    {
        
        
        $result = call_user_func_array(
        
        );
        
        if is_object($result) &&
            is_a($result, $this->delegateClass)
        ) {
           return $this->convertClass
           
            
        }
                
                
        
        
        
    }
    
    
    public function __call($name, $arguments)
    {
        //argumentsをforeachでclassをチェックし、convertClassしてreturnする
        $convertedArguments = $this->convertArguments($arguments);
        return call_user_func_array(
            $this->
            
            
        );
        
        
        
        
    }
    
    //引数の変換
    protected function convertArguments(
        array $arguments
    ){
        $convertedArguments = [];
        
        foreach($arguments as $target) {
            $convertedArguments[] =
                $this->isNeededConvertClass($target)?
                    $this->convertClass($target, $arguments):
                    $target;
        }
        return $convertedArguments;
    }
    
    
    
    
    
    
    
    
}







/////////////////////////////
//だめ


class BridgeClassOld implements MyInterface
{
    private $delegatdObject;
    
    public function __construct($id)
    {
        $this->delegatdObject = new LibClass($id);
    }
    
    //未定義methodは委譲
    public function __call($name, $arguments)
    {
        return call_user_func_array(
            [$this->delegatdObject, $name],
            $arguments
        );
    }
    
    //propertyがpublicなので、get/set等も委譲が必要
    //propertyアクセスは作らない方が良い
    
    
    
    
    
    //引数にLibInterfaceがあり、委譲できない
    //classを変換して実行
    //あるいはmethodを定義するのではなく、__call()の引数&returnがLibInterfaceの場合に変換する
    public function injected(MyInterface $obj)
    {
        $convertedClass = $this->convertClass($obj);
        return $this->delegatdObject->injected($convertedClass);
    }
    
    //変換用method
    private function convertClass(MyInterface $obj): LibInterface
    {
        return new LibClass($obj->id);
    }
    
    
    
    
    
    
}

/////////////////////////////


$idBridge1 = 'ブリッジ1';
$BridgeClass1 = new BridgeClass($idBridge1);
var_dump($BridgeClass1->id);

$idBridge2 = 'ブリッジ2';
$BridgeClass2 = new BridgeClass($idBridge2);
var_dump($BridgeClass2->id);

$BridgeClass1->injected($BridgeClass2);




