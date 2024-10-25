<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use candidate\accessor\impl\TypeCastImplTrait;
use candidate\accessor\TypeCastInterface;

class TestTypeCastImplTrait1 implements TypeCastInterface
{
    use TypeCastImplTrait;

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

class TypeCastImplTraitTest extends ConcertoTestCase
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

        $obj = new TestTypeCastImplTrait1();

        $obj->$prop = $data;
        $this->assertEquals($result, $obj->$prop);
    }

    public function actuallySetSuccessProvider()
    {
        $array = ['aaa' => 123, 'bbb' => 456];

        $obj = new \StdClass();
        $obj->aaa = 123;
        $obj->bbb = 456;

        $datetimeString = '2019-04-01 01:02:03';
        $datetime = new \DateTime($datetimeString);
        $date = (new \DateTime($datetimeString))->modify('today');

        return [
            ['prop_s', 123, '123'],
            ['prop_a', $obj, $array],
            ['prop_o', $array, $obj],
            ['prop_t', $datetimeString, $datetime],
            ['prop_d', $datetimeString, $date],
            ['prop_y', $datetime, $datetimeString],
        ];
    }

    /**
    *   @test
    *   @dataProvider actuallySetSuccessProvider
    */
    public function actuallySetSuccess($prop, $data, $result)
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestTypeCastImplTrait1();

        $obj->$prop = $data;
        $this->assertEquals($result, $obj->$prop);
    }
}
