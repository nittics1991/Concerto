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


//traitで汎用化できないか？
trait BridgeTrait
{
    
    //__call()でcall_user_func_arrayする事を考えるとobject?
    protected string $originalClassNamespace;
    protected string $delegateClassNamespace;
    
    //abstractで汎用化できないか?
    //引数は?
    abstract protected function convertClass($arguments);
    
    
    //methodでoriginal/delegateのnamespaceあるいはobjectを取得するmethodを用意する?
    protected function originalClass()
    {
        
    }
    protected function delegateClass()
    {
        
    }
    
    //magic methodはtrait内ではなく,classに作成する
    //同様に__getとかも必要なのでは?
    public function __call($name, $arguments)
    {
        //argumentsをforeachでclassをチェックし、convertClassしてreturnする
        $convertedArguments = $this->convertArguments($arguments);
        
        //引数は?
        return $this->callBridgeMethod($xxxx);
        
       
        
        
    }

    //__getとかはどうなるのか?
    //単純にcall_user_func_array([delegate, method], $arguments)になる?
    //ということは、__call()を使って呼び出す?
    public function __get($name) {
        return call_user_func_array([delegate, '__get'], $arguments);
    }
    public function __set($name, $arguments) {
        return call_user_func_array([delegate, '__get'], []);
    }
 

    
    
    //委譲したobjectで変換したargumentでmethod実行
    //引数は?
    public function callBridgeMethod($name, $arguments)
    {
        
        
        $result = call_user_func_array(
        
        );
        
        return $this->isNeededConvertClass($result)?
            $this->convertClass($result, $arguments):
            $result;
    }
    
    
    //全引数の変換
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
    
    //変換が必要か判定
    protected function isNeededConvertClass($target): bool
    {
        return is_object($result) &&
            is_a($result, $this->delegateClassNamespace);
                
        
        
        
        
    }
    
    
    
    
    
    
}


/////////////////////////////

//こんな感じで実行したい

$idBridge1 = 'ブリッジ1';
$BridgeClass1 = new BridgeClass($idBridge1);
var_dump($BridgeClass1->id);

$idBridge2 = 'ブリッジ2';
$BridgeClass2 = new BridgeClass($idBridge2);
var_dump($BridgeClass2->id);

$BridgeClass1->injected($BridgeClass2);

