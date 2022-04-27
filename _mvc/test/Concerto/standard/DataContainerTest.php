<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use ArrayObject;
use test\Concerto\ConcertoTestCase;
use Concerto\standard\DataContainer;

class _DataContainer extends DataContainer
{
    protected static $schema = [
        'b_data', 'i_data', 'f_data', 'd_data', 's_data', 'Z_DATA'
    ];

    public function isValidB_data($val)
    {
        return is_bool($val);
    }

    public function isValidI_data($val)
    {
        return (is_int($val)) ?  true : 'NG';
    }

    public function isValidF_data($val)
    {
        return (is_float($val)) ?    true : ['type_check_error' => $val];
    }

    public function isValidD_data($val)
    {
        return is_double($val);
    }

    public function isValidS_data($val)
    {
        return is_string($val);
    }
}


////////////////////////////////////////////////////////////////////////////////////////////////////


class DataContainerTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $this->object = new _DataContainer();
    }

    /**
    *   @test
    */
    public function basicOperation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //set and get
        $this->object->b_data = true;
        $this->assertEquals(true, $this->object->b_data);
        $this->assertEquals(true, $this->object['b_data']);

        $this->object->i_data = -10;
        $this->assertEquals(-10, $this->object->i_data);
        $this->assertEquals(-10, $this->object['i_data']);

        $this->object['s_data'] = 'STRING';
        $this->assertEquals('STRING', $this->object->s_data);
        $this->assertEquals('STRING', $this->object['s_data']);

        //isset
        $this->assertEquals(true, isset($this->object->b_data));
        $this->assertEquals(true, isset($this->object['i_data']));

        $this->assertEquals(false, isset($this->object->d_data));
        $this->assertEquals(false, isset($this->object['d_data']));

        //unset
        unset($this->object->b_data);
        $this->assertEquals(false, isset($this->object->b_data));
        $this->assertEquals(false, isset($this->object['b_data']));

        //isEmpty(part)
        $this->assertEquals(false, empty($this->object->i_data));
        $this->assertEquals(false, $this->object->isEmpty('i_data'));
        $this->object->i_data = 0;
        $this->assertEquals(true, $this->object->isEmpty('i_data'));

        //isNull(part)
        $this->assertEquals(false, is_null($this->object->i_data));
        $this->assertEquals(false, $this->object->isNull('i_data'));
        unset($this->object->i_data);
        $this->assertEquals(true, $this->object->isNull('i_data'));

        //isEmpty(full) and isNull(full) and unsetAll
        $this->assertEquals(false, empty($this->object));
        $this->assertEquals(false, $this->object->isEmpty());

        $this->assertEquals(false, is_null($this->object));
        $this->assertEquals(false, $this->object->isNull());

        unset($this->object->f_data);
        unset($this->object->s_data);
        $this->object->i_data = 0;

        $this->assertEquals(false, empty($this->object));
        $this->assertEquals(true, $this->object->isEmpty());
        $this->assertEquals(false, $this->object->isNull());

        $this->object->unsetAll();
        ;
        $this->assertEquals(false, empty($this->object));
        $this->assertEquals(false, is_null($this->object));
        $this->assertEquals(true, $this->object->isEmpty());
        $this->assertEquals(true, $this->object->isNull());

        //fromArray and toArray()
        $data = [
            'f_data' => -21.25,
            'd_data' => 33.12,
            's_data' => 'MOJI'
        ];

        $this->object->fromArray($data);
        $this->assertEquals($data['f_data'], $this->object->f_data);
        $this->assertEquals($data['d_data'], $this->object->d_data);
        $this->assertEquals($data['s_data'], $this->object->s_data);
        $this->assertEquals(null, $this->object->i_data);
        $this->assertEquals($data, $this->object->toArray());

        //getInfo
        $expect =  [
            'b_data', 'i_data', 'f_data', 'd_data', 's_data', 'Z_DATA'
        ];
        $this->assertEquals($expect, $this->object->getInfo());
        $this->assertEquals('d_data', $this->object->getInfo('d_data'));
    }

    /**
    *   @test
    */
    public function setException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no property called:dummy1');
        $this->object->dummy1 = 'DUMMY1';
    }

    /**
    *   @test
    */
    public function getException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('no property called:dummy1');
        $x = $this->object->dummy1;
    }

    /**
    *   @test
    */
    public function count1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [
            'f_data' => -21.25,
            's_data' => 'MOJI',
            'd_data' => 33.12
        ];
        $this->object->fromArray($data);
        $this->assertEquals(count($data), $this->object->count());
    }

    /**
    *   @test
    */
    public function getIterator()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [
            'f_data' => -21.25,
            's_data' => 'MOJI',
            'Z_DATA' => 'kigou',
            'd_data' => -1.12
        ];
        $this->object->fromArray($data);

        //foreach propertys
        $expect_keys = [
            'f_data',
            's_data',
            'Z_DATA',
            'd_data'
        ];

        $expect_vals = [
            -21.25,
            'MOJI',
            'kigou',
            -1.12
        ];

        $i = 0;
        foreach ($this->object as $key => $val) {
            $this->assertEquals($expect_keys[$i], $key);
            $this->assertEquals($expect_vals[$i], $val);
            $i++;
        }
    }

    /**
    *   @test
    */
    public function arrayDataOperation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->object->Z_DATA = range(1, 5);
        $this->assertEquals(range(1, 5), $this->object->Z_DATA);
        $this->assertEquals(range(1, 5), $this->object['Z_DATA']);

        //isset
        $this->assertEquals(true, isset($this->object->Z_DATA));
        $this->assertEquals(true, isset($this->object['Z_DATA']));

        //unset
        unset($this->object->Z_DATA);
        $this->assertEquals(false, isset($this->object->Z_DATA));
        $this->assertEquals(false, isset($this->object['Z_DATA']));

        //fromArray toArray
        $data = ['Z_DATA' => range(1, 5)];
        $this->object->fromArray($data);
        $this->assertEquals(range(1, 5), $this->object->Z_DATA);
        $this->assertEquals($data, $this->object->toArray());

        //isEmpty(part)
        unset($this->object->Z_DATA);
        $this->assertEquals(true, empty($this->object->Z_DATA));
        $this->assertEquals(true, $this->object->isEmpty('Z_DATA'));

        $this->object->Z_DATA = [];
        $this->assertEquals(true, empty($this->object->Z_DATA));
        $this->assertEquals(true, $this->object->isEmpty('Z_DATA'));

        $this->object->Z_DATA = [0];
        $this->assertEquals(false, empty($this->object->Z_DATA));
        $this->assertEquals(false, $this->object->isEmpty('Z_DATA'));

        //isNull(part)
        unset($this->object->Z_DATA);
        $this->assertEquals(true, is_null($this->object->Z_DATA));
        $this->assertEquals(true, $this->object->isNull('Z_DATA'));

        $this->object->Z_DATA = [];
        $this->assertEquals(false, is_null($this->object->Z_DATA));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));

        $this->object->Z_DATA = [0];
        $this->assertEquals(false, is_null($this->object->Z_DATA));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));
    }
}
