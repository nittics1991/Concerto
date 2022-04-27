<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use ArrayObject;
use test\Concerto\ConcertoTestCase;
use Concerto\standard\DataContainerValidatable;

class _DataContainerValidatable extends DataContainerValidatable
{
    protected static $schema = [
        'b_data', 'i_data', 'f_data', 'd_data', 's_data', 'Z_DATA'
    ];

    public function isValidB_data($val)
    {
        return is_null($val) || is_bool($val);
    }

    public function isValidI_data($val)
    {
        return (is_null($val) || is_int($val)) ?     true : 'NG';
    }

    public function isValidF_data($val)
    {
        return (is_null($val) || is_float($val)) ?   true : ['type_check_error' => $val];
    }

    public function isValidD_data($val)
    {
        return is_null($val) || is_double($val);
    }

    public function isValidS_data($val)
    {
        return is_null($val) || is_string($val);
    }

    protected function validCom($key, $val): bool
    {
        if (!isset($val)) {
            return true;
        }

        $result = parent::validCom($key, $val);

        if (!mb_ereg_match('\A[\x20-\x7e\x80-\xff\x09-\x0a\x0d]*\z', (string)$val)) {
            $this->valid[$key][] = 'invalid code';
            return false;
        }
        return $result;
    }

    //isValidAddressRecursive用
    protected function validRecursive($val)
    {
        return ($val) ? true : false;
    }
}

class TestDataContainerValidatable extends DataContainerValidatable
{
    protected static $schema = [
        'data_i', 'data_o', 'data_ao', 'data_c'
    ];

    public function __construct()
    {
        $this->data_o = new _DataContainerValidatable();
        $this->data_ao = [
            new _DataContainerValidatable(),
            new _DataContainerValidatable()
        ];
        $this->data_c = new \ArrayObject([
            new _DataContainerValidatable(),
            new _DataContainerValidatable()
        ]);
    }

    public function isValidData_i($val)
    {
        return (is_null($val) || is_int($val)) ?     true : 'NG';
    }

    public function isValidData_o($val)
    {
        return $val->isValid();
    }

    public function isValidData_ao($val)
    {
        $result = true;
        foreach ($val as $key => $obj) {
            $result = $obj->isValid() && $result;
        }
        return $result;
    }

    public function isValidData_c($val)
    {
        $result = true;
        foreach ($val as $key => $obj) {
            $result = $obj->isValid() && $result;
        }
        return $result;
    }
}


class TestDataContainerValidatableRelation extends _DataContainerValidatable
{
    //validRelation要
    protected function validRelation(): bool
    {
        $result = $this->i_data > $this->f_data;

        if (!$result) {
            $this->valid['isIntBiggerFloat'][] = 'error';
        }
        return $result;
    }
}


////////////////////////////////////////////////////////////////////////////////////////////////////


class DataContainerValidatableTest extends ConcertoTestCase
{
    private $object;

    protected function setUp(): void
    {
        $this->object = new _DataContainerValidatable();
    }

    /**
    *   @test
    */
    public function validCustom()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //non isValidXXX
        $args = ['Z_DATA' , 'abCD56#$漢字\nかなカタカタ'];
        $actual = $this->callPrivateMethod($this->object, 'validCustom', $args);
        $this->assertEquals(true, $actual);
    }

    /**
    *   @test
    */
    public function isValid()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->object->b_data = 'STRING';
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['b_data' => ['']], $this->object->getValidError());

        $this->object->unsetAll();
        $this->object->b_data = true;
        $this->assertEquals(true, $this->object->isValid());
        $this->assertEquals([], $this->object->getValidError());

        $this->object->unsetAll();
        $this->object->i_data = 'STRING';
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['i_data' => ['NG']], $this->object->getValidError());

        $this->object->unsetAll();
        $this->object->f_data = 'STRING';
        $this->assertEquals(false, $this->object->isValid());
        $this->assertEquals(['f_data' => ['type_check_error' => 'STRING']], $this->object->getValidError());

        $this->object->unsetAll();
        $this->object->f_data = $data = 'STRING' . chr(0x0b);
        $this->assertEquals(false, $this->object->isValid());
        $expect = ['f_data' => ['type_check_error' => $data, 0 => 'invalid code']];
        $this->assertEquals($expect, $this->object->getValidError());
    }

    public function validRecursiveErrorProvider()
    {
        return [
            [
                100,
                200,
                300,
                400,
                true,
                []
            ],

            [
                'DUMMY',
                200,
                300,
                400,
                false,
                ['data_i' => [0 => 'NG']
                ]
            ],

            [
                100,
                'DUMMY',
                300,
                400,
                false,
                ['data_o' =>
                    ['i_data' => [0 => 'NG']
                    ]
                ]
            ],

            [
                100,
                200,
                'DUMMY',
                400,
                false,
                ['data_ao' =>
                    [1 => [
                            'i_data' => [0 => 'NG']
                        ]
                    ]
                ]
            ],

            [
                100,
                200,
                300,
                'DUMMY',
                false,
                ['data_c' =>
                    [1 => [
                            'i_data' => [0 => 'NG']
                        ]
                    ]
                ]
            ],

        ];
    }

    /**
    *   @test
    *   @dataProvider validRecursiveErrorProvider
    */
    public function validRecursiveError($i, $o, $ao, $c, $valid, $error)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $object = new TestDataContainerValidatable();
        $object->data_i = $i;
        $object->data_o->i_data = $o;

        //$object->data_ao[1]->i_data = 300;
        $obj = $object['data_ao'][1];
        $obj->i_data = $ao;

        $object->data_c[1]->i_data = $c;

        $this->assertEquals($valid, $object->isValid());
        $this->assertEquals($error, $object->getValidError());
    }

    /**
    *   @test
    */
    public function isValidRecursive()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $actual = $this->callPrivateMethod(
            $this->object,
            'isValidRecursive',
            [
                [true, true, true],
                [$this->object, 'validRecursive']
            ]
        );
        $this->assertEquals(true, $actual);

        $actual = $this->callPrivateMethod(
            $this->object,
            'isValidRecursive',
            [
                [true, false, true],
                [$this->object, 'validRecursive']
            ]
        );
        $this->assertEquals(false, $actual);
    }

    /**
    *   @test
    */
    public function validRelation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestDataContainerValidatableRelation();

        $obj->i_data = 2;
        $obj->f_data = 1.0;
        $this->assertEquals(true, $obj->isValid());

        $obj->f_data = 3.0;
        $this->assertEquals(false, $obj->isValid());

        $error = $obj->getValidError();
        $this->assertEquals('error', $error['isIntBiggerFloat'][0]);
    }

    /**
    *   @test
    */
    public function statiCallValidateMethod()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            TestDataContainerValidatableRelation::validI_data(10),
            true
        );

        $this->assertEquals(
            TestDataContainerValidatableRelation::validI_data(1.25),
            'NG'
        );
    }
}
