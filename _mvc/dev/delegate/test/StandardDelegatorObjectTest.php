<?php

namespace Concerto\test\delegator;

use Concerto\test\ConcertoTestCase;
use Concerto\test\delegator\{
    DelegatedClass,
    LibClass
};

class StandardDelegatorObjectTest extends ConcertoTestCase
{
    /**
    *   @test
    *
    */
    public function delegatorNamespace(
    ) {
        $this->assertEquals(
            LibClass::class,
            $this->callPrivateMethod(
                'DelegatedClass',
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
            new LibClass('LibClass'),
            false,
        ],
        [
            new DelegatedClass('DelegatedClass'),
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
                'DelegatedClass',
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
            new LibClass('LibClass'),
            true,
        ],
        [
            new DelegatedClass('DelegatedClass'),
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
                'DelegatedClass',
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
            new LibClass('LibClass'),
            true,
        ],
        [
            new DelegatedClass('DelegatedClass'),
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
                'DelegatedClass',
                'convertToOriginal',
                [$obj]
            );
        } catch (Throwable $t) {
            $this->assertEquals(1, 0);
        }
        
        $this->assertEquals(
            $expect,
            $actual instanceof DelegatedClass
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
            new LibClass('LibClass'),
            false,
        ],
        [
            new DelegatedClass('DelegatedClass'),
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
                'DelegatedClass',
                'convertToDelegator',
                [$obj]
            );
        } catch (Throwable $t) {
            $this->assertEquals(1, 0);
        }
        
        $this->assertEquals(
            $expect,
            $actual instanceof DelegatedClass
        );
    }
    
    public function convertAllArgumentsUgingDelegatorProvider()
    {
       return [
        [
            [
                'オブジェクト以外',
                new \StdClass(),
                new LibClass('LibClass'),
                new DelegatedClass('DelegatedClass'),
            ],
            [
                'オブジェクト以外',
                new \StdClass(),
                new LibClass('LibClass'),
                new LibClass('DelegatedClass'),
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
        $actuals = $this->callPrivateMethod(
            'DelegatedClass',
            'convertAllArgumentsUgingDelegator',
            [$arguments]
        );
        
        $this->assertEquals(
            $expect,
            $actual
        );
    }
    
    
    
    
    
    
}
