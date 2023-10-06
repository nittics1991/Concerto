<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use candidate\accessor\impl\TypeCastImmutableImplTrait;
use candidate\accessor\TypeCastInterface;

class TestTypeCastImmutableImplTrait1 implements TypeCastInterface
{
    use TypeCastImmutableImplTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
        'prop_c', 'prop_n', 'prop_m', 'prop_t', 'prop_d', 'prop_y',
    ];

    protected $getCastTypes = [
        'prop_b' => 'bool',
        'prop_i' => 'int',
        'prop_f' => 'float',
        'prop_c' => 'binary',
        'prop_n' => 'null',
        'prop_m' => 'callable:getProp_m',
    ];

    protected $setCastTypes = [
        'prop_s' => 'string',
        'prop_a' => 'array',
        'prop_o' => 'object',
        'prop_t' => 'datetime',
        'prop_d' => 'date',
        'prop_y' => 'dateformat:Y-m-d H:i:s',
    ];

    protected function getProp_m($value)
    {
        return (string)$value . '_getProp_m';
    }
}

class TypeCastImmutableImplTraitTest extends ConcertoTestCase
{
    public function actuallyGetSuccessProvider()
    {
        return [
            ['prop_b', 123, true],
            ['prop_i', -123.45, -123],
            ['prop_f', '12', 12],
            ['prop_c', 7, 0b111],
            ['prop_n', 123, null],
            ['prop_m', 123, '123_getProp_m'],
        ];
    }

    /**
    *   @test
    *   @dataProvider actuallyGetSuccessProvider
    */
    public function actuallyGetSuccess($prop, $data, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestTypeCastImmutableImplTrait1();

        $this->setPrivateProperty($obj, 'dataContainer', [$prop => $data]);
        $this->assertEquals($result, $obj->$prop);
    }

    /**
    *   @test
    */
    public function dummyPropertyNameSetException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('this class is Immutable:DUMMY');

        $obj = new TestTypeCastImmutableImplTrait1();
        $obj->DUMMY = 123;
    }
}
