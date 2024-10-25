<?php

declare(strict_types=1);

namespace test\Concerto\accessor\impl;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
// use Concerto\accessor\AttributeInterface;
use Concerto\accessor\impl\AttributeImplTrait;

// class TestAttributeImplTrait1 implements
    // AttributeInterface
class TestAttributeImplTrait1
{
    use AttributeImplTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];
}

class AttributeImplTraitTest extends ConcertoTestCase
{
    /**
    *   @memo AttributeTraitとおなじテスト
    */
    #[Test]
    public function initialStateSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestAttributeImplTrait1();

        $this->assertEquals(true, $obj->definedProperty('prop_i'));
        $this->assertEquals(false, $obj->definedProperty('DUMMY'));
        $actual = [
            'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
        ];
        $this->assertEquals($actual, $obj->getDefinedProperty());
        $this->assertEquals(false, $obj->has('prop_i'));
    }

    /**
    *   @memo AttributeTraitとおなじテスト
    */
    #[Test]
    public function methodUnitTestSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestAttributeImplTrait1();
        //set
        $this->callPrivateMethod($obj, 'setDataToContainer', ['prop_i', 123]);
        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(['prop_i' => 123], $expect);
        //get
        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', ['prop_i' ]);
        $this->assertEquals(123, $expect);

        $this->assertEquals(true, $obj->has('prop_i'));
        //set 2件目
        $this->callPrivateMethod($obj, 'setDataToContainer', ['prop_f', 999.9]);
        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', ['prop_f' ]);
        $this->assertEquals(999.9, $expect);

        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(['prop_i' => 123, 'prop_f' => 999.9], $expect);
        //unset
        $expect = $this->callPrivateMethod($obj, 'unsetDataFromContainer', ['prop_i' ]);

        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', ['prop_i' ]);
        $this->assertEquals(null, $expect);

        $this->assertEquals(false, $obj->has('prop_i'));

        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(['prop_f' => 999.9], $expect);
    }

    /**
    *   @memo AttributeTraitとおなじテスト
    */
    #[Test]
    public function checkPropertyNameException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined property:DUMMY');

        $obj = new TestAttributeImplTrait1();
        $expect = $this->callPrivateMethod($obj, 'checkPropertyName', ['DUMMY' ]);
    }

    /**
    */
    #[Test]
    public function accessorSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestAttributeImplTrait1();
        //empty
        $this->assertEquals(false, isset($obj->prop_i));
        $this->assertEquals(null, $obj->prop_i);

        unset($obj->prop_i);
        $this->assertEquals(false, isset($obj->prop_i));

        //set
        $obj->prop_i = 123;
        $this->assertEquals(123, $obj->prop_i);
        $this->assertEquals(true, isset($obj->prop_i));

        $obj->prop_f = 999.9;
        $this->assertEquals(999.9, $obj->prop_f);
        $this->assertEquals(123, $obj->prop_i);
        $this->assertEquals(true, isset($obj->prop_f));
        $this->assertEquals(true, isset($obj->prop_i));

        //unset
        unset($obj->prop_i);
        $this->assertEquals(999.9, $obj->prop_f);
        $this->assertEquals(null, $obj->prop_i);
        $this->assertEquals(true, isset($obj->prop_f));
        $this->assertEquals(false, isset($obj->prop_i));
    }

    /**
    */
    #[Test]
    public function dummyPropertyNameGetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined property:DUMMY');

        $obj = new TestAttributeImplTrait1();
        $expect = $obj->DUMMY;
    }

    /**
    */
    #[Test]
    public function dummyPropertyNameSetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined property:DUMMY');

        $obj = new TestAttributeImplTrait1();
        $obj->DUMMY = 123;
    }

    /**
    */
    #[Test]
    public function dummyPropertyNameIssetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined property:DUMMY');

        $obj = new TestAttributeImplTrait1();
        isset($obj->DUMMY);
    }

    /**
    */
    #[Test]
    public function dummyPropertyNameUnsetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined property:DUMMY');

        $obj = new TestAttributeImplTrait1();
        unset($obj->DUMMY);
    }
}
