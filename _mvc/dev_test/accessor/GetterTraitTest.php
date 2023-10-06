<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use candidate\accessor\GetterTrait;
use candidate\accessor\GetterInterface;
use candidate\accessor\SetterTrait;
use candidate\accessor\SetterInterface;
use candidate\accessor\AttributeInterface;
use candidate\accessor\impl\AttributeImplTrait;

//StdClass
class TestGetterTrait1 implements GetterInterface
{
    use GetterTrait;

    protected $getterDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];

    public function __call($name, $args)
    {
        return $this->getter($name);
    }
}

//AttributeImplTrait
class TestGetterTrait2 implements
    GetterInterface,
    AttributeInterface
{
    use AttributeImplTrait;
    use GetterTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];

    protected $getterDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];

    public function __call($name, $args)
    {
        return $this->getter($name);
    }
}

//AttributeImplTrait & Setter
class TestGetterTrait3 implements
    GetterInterface,
    AttributeInterface,
    SetterInterface
{
    use AttributeImplTrait;
    use GetterTrait;
    use SetterTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o', 'both',
    ];

    protected $getterDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'both',
    ];

    protected $setterDefinitions = [
        'prop_s', 'prop_a', 'prop_o', 'both'
    ];

    public function __call($name, $args)
    {
        if ($this->isSetterMethod($name)) {
            return $this->setter($name, $args);
        }
        if ($this->isGetterMethod($name)) {
            return $this->getter($name, $args);
        }
        throw new \BadMethodCallException(
            "not defined method in __call:{$name}"
        );
    }
}

//prohibit property access. use getter
class TestSetterTrait4 extends TestGetterTrait2
{
    public function __get(string $name)
    {
        if (!$this->calledFromGetter()) {
            throw new \BadMethodCallException(
                "must be use getter method"
            );
        }
        return $this->getDataFromContainer($name);
    }
}

class GetterTraitTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function StdClassHasGetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait1();

        $this->assertEquals(true, $obj->hasGetter('prop_i'));
        $this->assertEquals(false, $obj->hasGetter('dummy'));
        $this->assertEquals(false, $obj->hasGetter('getProp_i'));
    }

    /**
    *   @test
    */
    public function StdClassIsGetterMethodSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait1();

        $this->assertEquals(true, $obj->isGetterMethod('getProp_i'));
        $this->assertEquals(false, $obj->isGetterMethod('getDummy'));
        $this->assertEquals(false, $obj->isGetterMethod('prop_i'));
    }

    /**
    *   @test
    */
    public function StdClassGetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait1();

        $obj->prop_i = 123;
        $obj->getProp_i(123);
        $this->assertEquals(123, $obj->prop_i);

        $obj->prop_f = 999.9;
        $this->assertEquals(999.9, $obj->prop_f);
        $this->assertEquals(123, $obj->prop_i);
    }

    /**
    *   @test
    */
    public function AttributeImpHasGetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait2();

        $this->assertEquals(true, $obj->hasGetter('prop_i'));
        $this->assertEquals(false, $obj->hasGetter('dummy'));
        $this->assertEquals(false, $obj->hasGetter('getProp_i'));
    }

    /**
    *   @test
    */
    public function AttributeImpIsGetterMethodSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait2();

        $this->assertEquals(true, $obj->isGetterMethod('getProp_i'));
        $this->assertEquals(false, $obj->isGetterMethod('getDummy'));
        $this->assertEquals(false, $obj->isGetterMethod('prop_i'));
    }

    /**
    *   @test
    */
    public function AttributeImplGetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait2();

        $this->setPrivateProperty($obj, 'dataContainer', ['prop_i' => 123]);
        $this->assertEquals(123, $obj->prop_i);

        $this->setPrivateProperty($obj, 'dataContainer', ['prop_i' => 123, 'prop_f' => 999.9]);
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

        $obj = new TestGetterTrait2();
        $expect = $this->callPrivateMethod($obj, 'getter', ['DUMMY' , [123]]);
    }

    /**
    *   @test
    */
    public function SetterGetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestGetterTrait3();

        //getter has
        $this->assertEquals(true, $obj->hasGetter('prop_i'));
        $this->assertEquals(true, $obj->isGetterMethod('getProp_i'));
        $this->assertEquals(false, $obj->hasGetter('dummy'));
        //setter has
        $this->assertEquals(true, $obj->hasSetter('prop_o'));
        $this->assertEquals(true, $obj->isSetterMethod('setProp_o'));
        $this->assertEquals(false, $obj->hasSetter('dummy'));
        //getter
        $this->setPrivateProperty($obj, 'dataContainer', ['prop_i' => 123]);
        $this->assertEquals(123, $obj->prop_i);
        $this->assertEquals(123, $obj->getProp_i());
        //setter
        $obj->setProp_o(999.9);
        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(true, array_key_exists('prop_o', $expect));
        $this->assertEquals(true, in_array(999.9, $expect));
        //both setter/getter
        $obj->setBoth(555);
        $this->assertEquals(555, $obj->getBoth());
    }

    /**
    *   @test
    */
    public function calledFromGetterSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestSetterTrait4();

        $this->setPrivateProperty($obj, 'dataContainer', ['prop_i' => 123]);
        $this->assertEquals(123, $obj->getProp_i());
    }

    /**
    *   @test
    */
    public function calledFromGetterException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('must be use getter method');

        $obj = new TestSetterTrait4();
        $expect = $obj->prop_i;
    }
}
