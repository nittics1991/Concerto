<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\ArrayUtil;

class ArrayUtil2Test extends ConcertoTestCase
{
    /**
    *
    *   @test
    */
    public function initArray()
    {
//      $this->markTestIncomplete();

        $actual = ArrayUtil::initArray('z', array('X', 'Y'), array('A', 'B', 'C'), array(1, 2));
        $expect = array(
            'X' => array(
                'A' => array(
                    1 => 'z',
                    2 => 'z'
                ),
                'B' => array(
                    1 => 'z',
                    2 => 'z'
                ),
                'C' => array(
                    1 => 'z',
                    2 => 'z'
                )
            ),
            'Y' => array(
                'A' => array(
                    1 => 'z',
                    2 => 'z'
                ),
                'B' => array(
                    1 => 'z',
                    2 => 'z'
                ),
                'C' => array(
                    1 => 'z',
                    2 => 'z'
                )
            )
        );
        $this->assertEquals($expect, $actual);

        $actual = ArrayUtil::initArray(null, array('A', 'B', 'C'), array(1, 2));
        $expect =  array(
            'A' => array(
                1 => null,
                2 => null
            ),
            'B' => array(
                1 => null,
                2 => null
            ),
            'C' => array(
                1 => null,
                2 => null
            )
        );
        $this->assertEquals($expect, $actual);
    }

    /**
    *
    *   @test
    */
    public function pivot()
    {
//      $this->markTestIncomplete();

        $x = array(
            array('mon' => '1', 'tgt' => 'tel', 'c' => 13, 'd' => 14, 'e' => 21, 'tanto' => 'A'),
            array('mon' => '1', 'tgt' => 'tel', 'c' => 11, 'd' => 12, 'e' => 22, 'tanto' => 'A'),
            array('mon' => '2', 'tgt' => 'adr', 'c' => 15, 'd' => 16, 'e' => 23, 'tanto' => 'B'),
            array('mon' => '2', 'tgt' => 'tel', 'c' => 17, 'd' => 18, 'e' => 24, 'tanto' => 'B'),
            array('mon' => '2', 'tgt' => 'tel', 'c' => 19, 'd' => 20, 'e' => 25, 'tanto' => 'A')
        );

        $expect = array(
            array('tgt' => 'mon', 1 => '1', 2 => '2'),
            'c' => array(
                array('tgt' => 'adr', 1 => 0,  2 => 15),
                array('tgt' => 'tel', 1 => 24, 2 => 36)
            ),
            'e' => array(
                array('tgt' => 'adr', 1 => 1, 2 => 23),
                array('tgt' => 'tel', 1 => 462, 2 => 600)
            )
        );

        $actual = ArrayUtil::pivot(
            $x,
            'tgt',
            'mon',
            array(
                'c' => 'array_sum',
                'e' => 'array_product'
            )
        );

        $this->assertEquals($expect, $actual);


        $expect = array(
            array('tgt' => 'tanto', 'A' => 'A', 'B' => 'B'),
            'c' => array(
                array('tgt' => 'adr', 'A' => 0,  'B' => 15),
                array('tgt' => 'tel', 'A' => 43, 'B' => 17)
            ),
        );

        $actual = ArrayUtil::pivot(
            $x,
            'tgt',
            'tanto',
            array(
                'c' => 'array_sum',
            )
        );

        $this->assertEquals($expect, $actual);
    }

    /**
    *
    *   @test
    */
    public function max()
    {
//      $this->markTestIncomplete();

        $data = array(11, 2, 3, 3.14, 16, 4);
        $this->assertEquals(16, ArrayUtil::max($data));

        $data = array(11, 2, 3, 3.14, 16, 'A', false, true);
        $this->assertEquals(16, ArrayUtil::max($data));

        $data = array();
        $this->assertEquals(null, ArrayUtil::max($data));

        $data = array(11, 'A', 'C', 'AA', 'B');
        $this->assertEquals('C', ArrayUtil::max($data, SORT_NATURAL));

        //
        $this->assertEquals(null, ArrayUtil::max([], SORT_NATURAL));
    }

    /**
    *
    *   @test
    */
    public function min()
    {
//      $this->markTestIncomplete();

        $data = array(11, 2, 3, 3.14, 16, 4);
        $this->assertEquals(2, ArrayUtil::min($data));

        $data = array(11, 2, 3, 3.14, 16, 'A', false, true);
        // $this->assertEquals(false, ArrayUtil::min($data));
        $this->assertEquals('A', ArrayUtil::min($data));

        $data = array();
        $this->assertEquals(null, ArrayUtil::min($data));

        $data = array('D', 'A', 'C', 'AA', 'B');
        $this->assertEquals('A', ArrayUtil::min($data, SORT_NATURAL));

        $this->assertEquals(null, ArrayUtil::min([], SORT_NATURAL));
    }

    /**
    *
    *   @test
    */
    public function first()
    {
//      $this->markTestIncomplete();

        $data = array(11, 2, 3, 3.14, 16, 4);
        $this->assertEquals(11, ArrayUtil::first($data));

        $data = array();
        $this->assertEquals(null, ArrayUtil::first($data));
    }

    /**
    *
    *   @test
    */
    public function last()
    {
//      $this->markTestIncomplete();

        $data = array(11, 2, 3, 3.14, 16, 4);
        $this->assertEquals(4, ArrayUtil::last($data));

        $data = array();
        $this->assertEquals(null, ArrayUtil::last($data));
    }
}
