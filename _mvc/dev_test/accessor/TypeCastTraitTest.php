<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use test\Concerto\ConcertoTestCase;
use candidate\accessor\TypeCastInterface;
use candidate\accessor\TypeCastTrait;

//StdClass
class TestTypeCastTrait1 implements TypeCastInterface
{
    use TypeCastTrait;

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

    public function __set($name, $value)
    {
        if ($this->hasSetCastType($name)) {
            $value = $this->toCastDataType(
                $this->setCastType($name),
                $value
            );
        }
        $this->$name = $value;
    }

    //getCastはpublicプロパティに保存されるため動かない
    public function __get($name)
    {
        $value = $this->$name;

        if ($this->hasGetCastType($name)) {
            return $this->toCastDataType(
                $this->getCastType($name),
                $value
            );
        }
        return $value;
    }
}

class TypeCastTraitTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function StdClassSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestTypeCastTrait1();

        //get
        $this->assertEquals(true, $obj->hasGetCastType('prop_i'));
        $this->assertEquals(false, $obj->hasGetCastType('DUMMY'));

        $actual = $this->getPrivateProperty($obj, 'getCastTypes');
        $this->assertEquals($actual, $obj->hasGetCastType());

        $expect = $this->callPrivateMethod($obj, 'getCastType', ['prop_i']);
        $this->assertEquals('int', $expect);

        //set
        $this->assertEquals(true, $obj->hasSetCastType('prop_o'));
        $this->assertEquals(false, $obj->hasSetCastType('DUMMY'));

        $actual = $this->getPrivateProperty($obj, 'setCastTypes');
        $this->assertEquals($actual, $obj->hasSetCastType());

        $expect = $this->callPrivateMethod($obj, 'setCastType', ['prop_o']);
        $this->assertEquals('object', $expect);
    }

    public function toCastDataTypeSuccessProvider()
    {
        $array = ['aaa' => 123, 'bbb' => 456];

        $obj = new \StdClass();
        $obj->aaa = 123;
        $obj->bbb = 456;

        $datetimeString = '2019-04-01 01:02:03';
        $datetime = new \DateTime($datetimeString);
        $date = (new \DateTime($datetimeString))->modify('today');

        return [
            ['bool', 123, true],
            ['int', 123.45, 123],
            ['float', '-999.9', -999.9],
            ['string', 123, '123'],
            ['binary', 7, 0b111],
            ['null', 123, null],
            ['callable:getProp_m', 'AAA', 'AAA_getProp_m'],
            ['array', $obj, $array],
            ['object', $array, $obj],
            ['datetime', $datetimeString, $datetime],
            ['date', $datetimeString, $date],
            ['dateformat:Y-m-d H:i:s', $datetime, $datetimeString],
        ];
    }

    /**
    *   @test
    *   @dataProvider toCastDataTypeSuccessProvider
    */
    public function toCastDataTypeSuccess($type, $val, $actual)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestTypeCastTrait1();
        $expect = $this->callPrivateMethod($obj, 'toCastDataType', [$type, $val]);
        $this->assertEquals($actual, $expect);
    }

    /**
    *   @test
    */
    public function setCastTypeException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('setCastTypes not defined:DUMMY');

        $obj = new TestTypeCastTrait1();
        $expect = $this->callPrivateMethod($obj, 'setCastType', ['DUMMY']);
    }

    /**
    *   @test
    */
    public function getCastTypeException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('getCastTypes not defined:DUMMY');

        $obj = new TestTypeCastTrait1();
        $expect = $this->callPrivateMethod($obj, 'getCastType', ['DUMMY']);
    }

    public function actuallyGetSuccessProvider()
    {
        return [
            ['prop_b', 123, 123],
            ['prop_i', -123.45, -123.45],
            ['prop_f', '12', '12'],
            ['prop_c', 7, 7],
            ['prop_n', 123, 123],
            ['prop_m', 123, 123],
        ];
    }

    /**
    *   @test
    *   @dataProvider actuallyGetSuccessProvider
    */
    public function actuallyGetSuccess($prop, $data, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestTypeCastTrait1();
        //getterは動作しない
        $obj->$prop = $data;
        $this->assertEquals($result, $obj->$prop);
    }

    public function actuallyGSetSuccessProvider()
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
    *   @dataProvider actuallyGSetSuccessProvider
    */
    public function actuallyGSetSuccess($prop, $data, $result)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestTypeCastTrait1();

        $obj->$prop = $data;
        $this->assertEquals($result, $obj->$prop);
    }
}
