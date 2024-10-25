<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\ArrayAccessObject;

class ArrayAccessObjectTest extends ConcertoTestCase
{
    private $object;

    protected function setUp(): void
    {
        $this->object = new ArrayAccessObject();
    }

    /**
    */
    #[Test]
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
    }

    /**
    */
    #[Test]
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
    */
    #[Test]
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
    */
    #[Test]
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

    /**
    */
    #[Test]
    public function arrayMultiDataOperation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = [
            'a1' => 1,
            'a2' => [
                'b21' => 21,
                'b22' => [
                    'c221' => 221
                ],
                'b23' => 23
            ]
        ];

        $this->object->Z_DATA = $data;
        $this->assertEquals($data, $this->object->Z_DATA);

        $this->assertEquals(1, $this->object->Z_DATA['a1']);
        $this->assertEquals(221, $this->object->Z_DATA['a2']['b22']['c221']);

        $this->assertEquals(false, $this->object->isEmpty('Z_DATA'));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));

        $data2 = $data;
        array_walk_recursive(
            $data2,
            function (&$val, $key) {
                $val = 0;
            }
        );

        $this->assertEquals(false, $this->object->isEmpty('Z_DATA'));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));

        $data2 = $data;
        array_walk_recursive(
            $data2,
            function (&$val, $key) {
                $val = null;
            }
        );

        $this->assertEquals(false, $this->object->isEmpty('Z_DATA'));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));
    }

    /**
    */
    #[Test]
    public function objectDataOperation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new \StdClass();
        $obj->data = 11;

        $this->object->Z_DATA = $obj;
        $this->assertEquals($obj, $this->object->Z_DATA);
        $this->assertEquals($obj, $this->object['Z_DATA']);

        //isset
        $this->assertEquals(true, isset($this->object->Z_DATA));
        $this->assertEquals(true, isset($this->object['Z_DATA']));

        //unset
        unset($this->object->Z_DATA);
        $this->assertEquals(false, isset($this->object->Z_DATA));
        $this->assertEquals(false, isset($this->object['Z_DATA']));

        //fromArray toArray
        $data = ['Z_DATA' => $obj];
        $this->object->fromArray($data);
        $this->assertEquals($obj, $this->object->Z_DATA);
        $this->assertEquals($data, $this->object->toArray());

        //isEmpty(part)
        unset($this->object->Z_DATA);
        $this->assertEquals(true, empty($this->object->Z_DATA));
        $this->assertEquals(true, $this->object->isEmpty('Z_DATA'));

        $this->object->Z_DATA = [];
        $this->assertEquals(true, empty($this->object->Z_DATA));
        $this->assertEquals(true, $this->object->isEmpty('Z_DATA'));

        $obj->data = 0;
        $this->object->Z_DATA = $obj;
        $this->assertEquals(false, empty($this->object->Z_DATA));
        $this->assertEquals(false, $this->object->isEmpty('Z_DATA'));

        //isNull(part)
        unset($this->object->Z_DATA);
        $this->assertEquals(true, is_null($this->object->Z_DATA));
        $this->assertEquals(true, $this->object->isNull('Z_DATA'));

        $this->object->Z_DATA = [];
        $this->assertEquals(false, is_null($this->object->Z_DATA));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));

        $obj->data = 0;
        $this->object->Z_DATA = $obj;
        $this->assertEquals(false, is_null($this->object->Z_DATA));
        $this->assertEquals(false, $this->object->isNull('Z_DATA'));
    }

    /**
    */
    #[Test]
    public function objectMultiDataOperation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = new \StdClass();
        $data->a1 = 11;

        $inner = new \StdClass();
        $inner->a21 = 21;
        $data->a2 = $inner;

        $this->object->Z_DATA = $data;
        $this->assertEquals($data, $this->object->Z_DATA);

        $this->assertEquals(11, $this->object->Z_DATA->a1);
        $this->assertEquals(true, $this->object->Z_DATA->a2 instanceof \StdClass);
        $this->assertEquals($inner, $this->object->Z_DATA->a2);

        $this->assertEquals(21, $this->object->Z_DATA->a2->a21);
    }
}
