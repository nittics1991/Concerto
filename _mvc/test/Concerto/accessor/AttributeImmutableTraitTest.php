<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use Concerto\accessor\AttributeImmutableTrait;
use Concerto\accessor\AttributeImmutableInterface;
use Concerto\accessor\AttributeInterface;
use Concerto\accessor\impl\AttributeImplTrait;

class TestAttributeImmutableTrait1 implements
    AttributeInterface,
    AttributeImmutableInterface
{
    use AttributeImplTrait, AttributeImmutableTrait {
            AttributeImmutableTrait::__set insteadof AttributeImplTrait;
            AttributeImmutableTrait::__unset insteadof AttributeImplTrait;
    }

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];
}

class AttributeImmutableTraitTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function accessorSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestAttributeImmutableTrait1();
        //empty
        $this->assertEquals(false, isset($obj->prop_i));
        $this->assertEquals(null, $obj->prop_i);

        //manyual set
        $this->setPrivateProperty($obj, 'dataContainer', ['prop_i' => 123]);
        $this->assertEquals(123, $obj->prop_i);
        $this->assertEquals(true, isset($obj->prop_i));

        $this->setPrivateProperty($obj, 'dataContainer', ['prop_i' => 123, 'prop_f' => 999.9]);
        $this->assertEquals(999.9, $obj->prop_f);
        $this->assertEquals(123, $obj->prop_i);
        $this->assertEquals(true, isset($obj->prop_f));
        $this->assertEquals(true, isset($obj->prop_i));
    }

    /**
    *   @test
    */
    public function immutableSetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('his class is Immutable:prop_i');

        $obj = new TestAttributeImmutableTrait1();
        $obj->prop_i = 123;
    }

    /**
    *   @test
    */
    public function immutableUnsetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('his class is Immutable:prop_i');

        $obj = new TestAttributeImmutableTrait1();
        unset($obj->prop_i);
    }
}
