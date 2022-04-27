<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use Concerto\accessor\ArrayAccessTrait;
use Concerto\accessor\AttributeInterface;
use Concerto\accessor\impl\AttributeImplTrait;
use ArrayAccess;

class TestArrayAccessTrait1 implements
    ArrayAccess,
    AttributeInterface
{
    use AttributeImplTrait;
    use ArrayAccessTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];
}

class ArrayAccessTraitTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestArrayAccessTrait1();

        $this->assertEquals(false, isset($obj['prop_i']));

        $obj->prop_i = 123;
        $this->assertEquals(true, isset($obj['prop_i']));
        $this->assertEquals(123, $obj['prop_i']);

        $obj['prop_f'] = 999.9;
        $this->assertEquals(999.9, $obj['prop_f']);
        $this->assertEquals(999.9, $obj->prop_f);

        unset($obj['prop_i']);
        $this->assertEquals(false, isset($obj['prop_i']));
        $this->assertEquals(true, isset($obj['prop_f']));
    }
}
