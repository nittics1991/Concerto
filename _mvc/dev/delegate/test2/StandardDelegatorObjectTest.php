<?php

namespace Concerto\test\delegator;

use Concerto\test\ConcertoTestCase;
use Concerto\test\delegator\MyClass;

class StandardDelegatorObjectTest extends ConcertoTestCase
{
    /**
    *   @test
    *
    */
    public function delegatorNamespace(
    ) {
        $this->assertEquals(
            MyClass::class,
            $this->callPrivateMethod(
                'MyClass',
                'delegatorNamespace',
                []
            )
        );
    }
    
    public function isOriginalObjectProvider()
    {
       return [
        [
            'オブジェクト以外',
            false,
        ],
        [
            new \StdClass(),
            false,
        ],
        [
            new DateTimeImmutable('2000-12-31 000000'),
            false,
        ],
        [
            new MyClass('2000-12-31 000000'),
            true,
        ],
       ]; 
    }
    
    /**
    *   @test
    *   @dataProvider isOriginalObjectProvider
    */
    public function isOriginalObject(
        $target,
        $expect
    ) {
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                'MyClass',
                'isOriginalObject',
                [$target]
            )
        );
    }
    
    public function isDelegatorObjectProvider()
    {
       return [
        [
            'オブジェクト以外',
            false,
        ],
        [
            new \StdClass(),
            false,
        ],
        [
            new DateTimeImmutable('2000-12-31 000000'),
            true,
        ],
        [
            new MyClass('2000-12-31 000000'),
            false   ,
        ],
       ]; 
    }
    
    /**
    *   @test
    *   @dataProvider isDelegatorObjectProvider
    */
    public function isDelegatorObject(
        $target,
        $expect
    ) {
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                'MyClass',
                'isDelegatorObject',
                [$target]
            )
        );
    }
    
    public function convertToOriginalProvider()
    {
       return [
        [
            'オブジェクト以外',
            false,
        ],
        [
            new \StdClass(),
            false,
        ],
        [
            new DateTimeImmutable('2000-12-31 000000'),
            true,
        ],
        [
            new MyClass('MyClass'),
            false,
        ],
       ]; 
    }
    
    /**
    *   @test
    *   @dataProvider convertToOriginalProvider
    */
    public function convertToOriginal(
        $obj,
        $expect
    ) {
        try {
            $actual = $this->callPrivateMethod(
                'MyClass',
                'convertToOriginal',
                [$obj]
            );
        } catch (Throwable $t) {
            $this->assertEquals(1, 0);
        }
        
        $this->assertEquals(
            $expect,
            $actual instanceof MyClass
        );
    }
    
    public function convertToDelegatorProvider()
    {
       return [
        [
            'オブジェクト以外',
            false,
        ],
        [
            new \StdClass(),
            false,
        ],
        [
            new DateTimeImmutable('2000-12-31 000000'),
            false,
        ],
        [
            new MyClass('MyClass'),
            true,
        ],
       ]; 
    }
    
    /**
    *   @test
    *   @dataProvider convertToDelegatorProvider
    */
    public function convertToDelegator(
        $obj,
        $expect
    ) {
        try {
            $actual = $this->callPrivateMethod(
                'MyClass',
                'convertToDelegator',
                [$obj]
            );
        } catch (Throwable $t) {
            $this->assertEquals(1, 0);
        }
        
        $this->assertEquals(
            $expect,
            $actual instanceof MyClass
        );
    }
    
    public function convertAllArgumentsUgingDelegatorProvider()
    {
       return [
        [
            [
                'オブジェクト以外',
                new \StdClass(),    //オブジェクト
                new DateTimeImmutable('2000-12-1 000000'), //delegate class
                new MyClass('MyClass'), //oroginal class
            ],
            [
                'オブジェクト以外',
                new \StdClass(),    //オブジェクト
                new DateTimeImmutable('2000-12-1 000000'), //delegate class
                new MyClass('MyClass'), //oroginal class
            ],
        ]
       ]; 
    }
    
    /**
    *   @test
    *   @dataProvider convertAllArgumentsUgingDelegatorProvider
    */
    public function convertAllArgumentsUgingDelegator(
        $arguments,
        $expect
    ) {
        $actual = $this->callPrivateMethod(
            'MyClass',
            'convertAllArgumentsUgingDelegator',
            [$arguments]
        );
        
        $this->assertEquals(
            $expect,
            $actual
        );
    }
    
    
    
    
    public function convertAndExecuteAllArgumentsAndResultProvider()
    {
       $datetime = '2000-12-31 00:00:00';
       $object = new MyClass($datetime);
       
       return [
        [
            $object,
            'format',
            [DateTimeImmutable::ATOM],
            $datetime
        ],
        
        
        
        ]; 
    }
    
    /**
    *   @test
    *   @dataProvider convertAndExecuteAllArgumentsAndResultProvider
    */
    public function convertAndExecuteAllArgumentsAndResult(
        $object,
        $method_name,
        $arguments,
        $expect
    ) {
        $actual = $this->callPrivateMethod(
            [$object,$method_name],
            'convertAndExecuteAllArgumentsAndResult',
            [$arguments]
        );
        
        $this->assertEquals(
            $expect,
            $actual
        );
    }
    
    
    
}
