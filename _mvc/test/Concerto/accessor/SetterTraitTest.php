<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use Concerto\accessor\SetterInterface;
use Concerto\accessor\SetterTrait;
use Concerto\accessor\AttributeInterface;
use Concerto\accessor\impl\AttributeImplTrait;

//StdClass
class TestSetterTrait1 implements SetterInterface
{
    use SetterTrait;

    protected $setterDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];

    public function __call($name, $args)
    {
        $this->setter($name, $args);
    }
}

//AttributeImplTrait
class TestSetterTrait2 implements
    SetterInterface,
    AttributeInterface
{
    use AttributeImplTrait;
    use SetterTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];

    protected $setterDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];

    public function __call($name, $args)
    {
        $this->setter($name, $args);
    }
}

//prohibit property access. use setter
class TestSetterTrait3 extends TestSetterTrait2
{
    public function __set(string $name, $value): void
    {
        if (!$this->calledFromSetter()) {
            throw new \BadMethodCallException(
                "must be use setter method"
            );
        }
        $this->setDataToContainer($name, $value);
    }
}

class SetterTraitTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function StdClassHasSetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait1();

        $this->assertEquals(true, $obj->hasSetter('prop_i'));
        $this->assertEquals(false, $obj->hasSetter('dummy'));
        $this->assertEquals(false, $obj->hasSetter('setProp_i'));
    }

    /**
    *   @test
    */
    public function StdClassIsSetterMethodSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait1();

        $this->assertEquals(true, $obj->isSetterMethod('setProp_i'));
        $this->assertEquals(false, $obj->isSetterMethod('setDummy'));
        $this->assertEquals(false, $obj->isSetterMethod('prop_i'));
    }

    /**
    *   @test
    */
    public function StdClassSetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait1();

        $obj->setProp_i(123);
        $this->assertEquals(123, $obj->prop_i);

        $obj->setProp_f(999.9);
        $this->assertEquals(999.9, $obj->prop_f);
        $this->assertEquals(123, $obj->prop_i);
    }

    /**
    *   @test
    */
    public function AttributeImpHasSetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait2();

        $this->assertEquals(true, $obj->hasSetter('prop_i'));
        $this->assertEquals(false, $obj->hasSetter('dummy'));
        $this->assertEquals(false, $obj->hasSetter('setProp_i'));
    }

    /**
    *   @test
    */
    public function AttributeImpIsSetterMethodSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait2();

        $this->assertEquals(true, $obj->isSetterMethod('setProp_i'));
        $this->assertEquals(false, $obj->isSetterMethod('setDummy'));
        $this->assertEquals(false, $obj->isSetterMethod('prop_i'));
    }

    /**
    *   @test
    */
    public function AttributeImplSetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait2();

        $obj->setProp_i(123);
        $this->assertEquals(123, $obj->prop_i);

        $obj->setProp_f(999.9);
        $this->assertEquals(999.9, $obj->prop_f);
        $this->assertEquals(123, $obj->prop_i);
    }

    /**
    *   @test
    */
    public function notDefinedProperyException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('not defined method:DUMMY');

        $obj = new TestSetterTrait1();
        $expect = $this->callPrivateMethod($obj, 'setter', ['DUMMY' , [123]]);
    }

    /**
    *   @test
    */
    public function calledFromSetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait3();

        $obj->setProp_i(123);
        $this->assertEquals(123, $obj->prop_i);
    }

    /**
    *   @test
    */
    public function calledFromSetterException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('must be use setter method');

        $obj = new TestSetterTrait3();
        $obj->prop_i = 123;
        ;
    }
}
