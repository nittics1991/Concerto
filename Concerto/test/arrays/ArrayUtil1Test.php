<?php

declare(strict_types=1);

namespace Concerto\test\arrays;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\ArrayUtil;

class ArrayUtil1Test extends ConcertoTestCase
{
    public static function datatableProvider()
    {

        $a = array('name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2);
        $b = array('name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5);
        $c = array('name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7);
        $d = array('name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11);

        $ar = array($a, $b, $c, $d);

        $x = array(1, 2, 3);
        $y = array(11, 12, 13);
        $z = array(101, 102, 103);

        $br = array($x, $y, $z);

        return array(
            array($a, $b, $c, $d, $x, $y, $z, $ar, $br)
        );
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function alignKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = ArrayUtil::alignKey(array($a, $b, $c, $d));
        $expected = array(
            array('name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2, 'aaa' => null, 'bbb' => null)
            , array('name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5, 'aaa' => null, 'bbb' => null)
            , array('name' => null,'age' => 34,'adr' => null, 'id' => 7, 'aaa' => null, 'bbb' => null)
            , array('name' => null,'age' => 22,'adr' => null, 'id' => null, 'aaa' => 'AAA', 'bbb' => 'BBB')
        );
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::alignKey(array($x, $y));
        $expected = array(
            array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => 1, 7 => 2, 8 => 3)
            , array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => 11, 7 => 12, 8 => 13)

        );
        $this->assertEquals($expected, $actual);

        //
        $this->assertEquals([[]], ArrayUtil::alignKey([[]]));
    }

    public static function arrayKeyData()
    {

        $a = array('name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2);
        $b = array('id' => 5, 'adr' => 'osaka','age' => 22, 'name' => 'BBB');
        $c = array('age' => 34, 'id' => 7);
        $d = array('aaa' => 'AAA', 'age' => 22, 'bbb' => 'BBB');

        $x = array(1, 2, 3);
        $y = array(11, 12, 13);

        return array(
            array($a, $b, $c, $d, $x, $y)
        );
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function expansion($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::expansion($ar, 'name', 'id');
        $expected = array('AAA' => 2, 'BBB' => 5, 'CCC' => 7, 'FFF' => 11);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::expansion($br, 1, 0);
        $expected = array(2 => 1, 12 => 11, 102 => 101);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::expansion($ar, 'name', 'id', function ($keys, $vals) {
            $result = array();
            $mem = 0;

            for (
                $i = 0, $length = count($keys);
                $i < $length;
                $i++
            ) {
                $mem = $result[$keys[$i]] = $mem + $vals[$i];
            }
            return $result;
        });
        $expected = array('AAA' => 2, 'BBB' => 7, 'CCC' => 14, 'FFF' => 25);
        $this->assertEquals($expected, $actual);

        //
        try {
            $actual = ArrayUtil::expansion([[]], 'name', 'id');
            $this->fail("not trigger exception");
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function extractKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $keys = array('name', 'id');
        $actual = ArrayUtil::extractKey($a, $keys);
        $this->assertEquals(array('name' => $a['name' ], 'id' => $a['id']), $actual);

        $keys = array(1, 2);
        $actual = ArrayUtil::extractKey($x, $keys);
        $this->assertEquals(array(1 => $x[1], 2 => $x[2]), $actual);

        //
        $keys = array('NAME', 'ID');
        $actual = ArrayUtil::extractKey($a, $keys);
        $this->assertEquals(['NAME' => null, 'ID' => null], $actual);

        $keys = array('name', 'id');
        $actual = ArrayUtil::extractKey([], $keys);
        $this->assertEquals(['name' => null, 'id' => null], $actual);

        $actual = ArrayUtil::extractKey($a, []);
        $this->assertEquals([], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function groupBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::groupBy(
            $ar,
            array('age'),
            array('id'
                     => function ($array) {
                        return array_sum($array);
                     }
                )
        );

        $expected = array(
            array('age' => 16, 'id' => 2)
            , array('age' => 22, 'id' => 16)
            , array('age' => 34, 'id' => 7)
        );
        $this->assertEquals($expected, $actual);


        $actual = ArrayUtil::groupBy(
            $br,
            array(1),
            array(0 => 'array_sum')
        );

        $expected = array(
            array(2, 1)
            , array(12, 11)
            , array(102, 101)
        );
        $this->assertEquals($expected, $actual);

        $a = array('name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2);
        $b = array('name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5);
        $c = array('name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7);
        $d = array('name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11);
        $e = array('name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 15);
        $ar =  array($a, $b, $c, $d, $e);


        $actual = ArrayUtil::groupBy(
            $ar,
            array('age', 'name'),
            array('id'
                     => function ($array) {
                        return array_sum($array);
                     }
                )
        );

        $expected = array(
            array('age' => 16, 'name' => 'AAA', 'id' => 2)
            , array('age' => 22, 'name' => 'BBB', 'id' => 20)
            , array('age' => 22, 'name' => 'FFF', 'id' => 11)
            , array('age' => 34, 'name' => 'CCC', 'id' => 7)
        );
        $this->assertEquals($expected, $actual);

        //
        try {
            $actual = ArrayUtil::groupBy(
                [[]],
                array('age'),
                array('id'
                        => function ($array) {
                            return array_sum($array);
                        }
                    )
            );
            $this->fail("not trigger exception");
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
    * @test
    * @dataProvider datatableProvider
    *
    */
    public function isDimension($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = ArrayUtil::isDimension($a, 1);
        $this->assertEquals(true, $actual);

        $actual = ArrayUtil::isDimension(array($a, $b));
        $this->assertEquals(true, $actual);

        $actual = ArrayUtil::isDimension(array($a, $b), 3);
        $this->assertEquals(false, $actual);

        $actual = ArrayUtil::isDimension(array($a, 'aaa', $b));
        $this->assertEquals(false, $actual);

        //
        $actual = ArrayUtil::isDimension(
            [
                [
                    [$a]
                ]
            ],
            3
        );
        $this->assertEquals(true, $actual);

        $actual = ArrayUtil::isDimension([], 1);
        $this->assertEquals(true, $actual);
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function mergeKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = ArrayUtil::mergeKey($d, $b, $a, $c);
        ksort($actual);
        $expected = array('aaa' => null, 'adr' => null, 'age' => null, 'bbb' => null, 'id' => null, 'name' => null);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::mergeKey($a, $b);
        ksort($actual);
        $expected = array('adr' => null, 'age' => null, 'id' => null, 'name' => null);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::mergeKey($x, $y);
        ksort($actual);
        $expected = array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null);
        $this->assertEquals($expected, $actual);

        //
        $actual = ArrayUtil::mergeKey($x, []);
        $expected = array(0 => null, 1 => null, 2 => null);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::mergeKey([], $x);
        $expected = array(0 => null, 1 => null, 2 => null);
        $this->assertEquals($expected, $actual);
    }

    /**
    *
    */
    public function mergeKeyException()
    {

//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type is different');
        $actual = ArrayUtil::mergeKey(array(), 'AA');
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function mergeKeyArray($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = ArrayUtil::mergeKeyArray(array($d, $b, $a, $c));
        ksort($actual);
        $expected = array('aaa' => null, 'adr' => null, 'age' => null, 'bbb' => null, 'id' => null, 'name' => null);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::mergeKeyArray(array($a, $b));
        ksort($actual);
        $expected = array('adr' => null, 'age' => null, 'id' => null, 'name' => null);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::mergeKeyArray(array($x, $y));
        ksort($actual);
        $expected = array(0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null);
        $this->assertEquals($expected, $actual);

        //
        $actual = ArrayUtil::mergeKeyArray([$x, []]);
        $expected = array(0 => null, 1 => null, 2 => null);
        $this->assertEquals($expected, $actual);

        $actual = ArrayUtil::mergeKeyArray([[], $x]);
        $expected = array(0 => null, 1 => null, 2 => null);
        $this->assertEquals($expected, $actual);
    }

    /**
    * @test
    */
    public function mergeKeyArrayException()
    {

//      $this->markTestIncomplete();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data type is different');
        $actual = ArrayUtil::mergeKeyArray(array(1, 2, 3));
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function orderBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::orderBy($ar, array('age', 'name'), array(SORT_DESC , SORT_ASC ), null);
        $expected = array(
            array('name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7)
            , array('name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5)
            , array('name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11)
            , array('name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2)
        );
        $this->assertEquals($expected, $actual);


        $actual = ArrayUtil::orderBy($br, array(0), array(SORT_DESC), null);
        $expected = array(
            array(101, 102, 103)
            , array(11, 12, 13)
            , array(1, 2, 3)
        );
        $this->assertEquals($expected, $actual);

        //
        $actual = ArrayUtil::orderBy([[]], array('age', 'name'), array(SORT_DESC , SORT_ASC ), null);
        $this->assertEquals([[]], $actual);
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function positionKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = ArrayUtil::positionKey($a, 'age');
        $this->assertEquals(1, $actual);

        $actual = ArrayUtil::positionKey($x, 2);
        $this->assertEquals(2, $actual);

        $actual = ArrayUtil::positionKey($x, 5);
        $this->assertEquals(false, $actual);

        //
        $actual = ArrayUtil::positionKey($a, 'DUMMY');
        $this->assertEquals(false, $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function rotate($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {

        $actual = ArrayUtil::rotate($x, 1);
        $this->assertEquals(array(2, 3, 1), $actual);

        $actual = ArrayUtil::rotate($x, 2);
        $this->assertEquals(array(3, 1, 2), $actual);

        $actual = ArrayUtil::rotate($x, -1);
        $this->assertEquals(array(3, 1, 2), $actual);

        $actual = ArrayUtil::rotate($a, -1);
        $this->assertEquals(array('adr' => 'tokyo', 'id' => 2, 'name' => 'AAA','age' => 16), $actual);

        //
        $actual = ArrayUtil::rotate([], 1);
        $this->assertEquals([], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function stepwise($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {

        $actual = ArrayUtil::stepwise($x, function ($val1, $val2) {
                return $val1 + $val2;
        });
        $this->assertEquals(array(1, 3, 6), $actual);

        $actual = ArrayUtil::stepwise($y, function ($val1, $val2) {
                return $val1 + $val2;
        });
        $this->assertEquals(array(11, 23, 36), $actual);

        $actual = ArrayUtil::stepwise($x, function ($val1, $val2) {
                return $val1 * $val2;
        }, 10);
        $this->assertEquals(array(10, 20, 60), $actual);

        //
        $actual = ArrayUtil::stepwise([], function ($val1, $val2) {
                return $val1 + $val2;
        });
        $this->assertEquals([], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function selectBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::selectBy($ar, array('id', 'name'));
        $expected = array(
            array('id' => 2, 'name' => 'AAA')
            , array('id' => 5, 'name' => 'BBB')
            , array('id' => 7, 'name' => 'CCC')
            , array('id' => 11, 'name' => 'FFF')
        );

        $this->assertEquals($expected, $actual);


        $actual = ArrayUtil::selectBy($br, array(2, 0));
        $expected = array(
            array(2 => 3, 0 => 1)
            , array(2 => 13, 0 => 11)
            , array(2 => 103, 0 => 101)
        );
        $this->assertEquals($expected, $actual);

        //
        $actual = ArrayUtil::selectBy($ar, []);
        $this->assertEquals([[]], $actual);

        $actual = ArrayUtil::selectBy([[]], array('id', 'name'));
        $this->assertEquals([[]], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function selectBy2($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::selectBy($ar, array('id', 'DMY', 'name'));
        $expected = array(
            array('id' => 2, 'DMY' => null, 'name' => 'AAA')
            , array('id' => 5, 'DMY' => null, 'name' => 'BBB')
            , array('id' => 7, 'DMY' => null, 'name' => 'CCC')
            , array('id' => 11, 'DMY' => null, 'name' => 'FFF')
        );
        $this->assertEquals($expected, $actual);


        $actual = ArrayUtil::selectBy($ar, array('id', 'name', 'DMY'), ['DMY' => 'INIT', 'id' => 'X']);
        $expected = array(
            array('id' => 2, 'name' => 'AAA', 'DMY' => 'INIT')
            , array('id' => 5, 'name' => 'BBB', 'DMY' => 'INIT')
            , array('id' => 7, 'name' => 'CCC', 'DMY' => 'INIT')
            , array('id' => 11, 'name' => 'FFF', 'DMY' => 'INIT')
        );
        $this->assertEquals($expected, $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function toFillBlank($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::toFillBlank(
            $ar,
            'id',
            range(1, 10),
            array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp')
        );
        $actual = ArrayUtil::orderBy($actual, array('id'));

        $expected = array(
            array('name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2)
            , array('name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5)
            , array('name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7)
            , array('name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 1)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 3)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 4)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 6)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 8)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 9)
            , array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 10)
        );
        $expected = ArrayUtil::orderBy($expected, array('id'));

        $this->assertEquals($expected, $actual);

        //
        $actual = ArrayUtil::toFillBlank(
            array($x, $y),
            1,
            range(1, 15)
        );
        $actual = ArrayUtil::orderBy($actual, array(1));

        $expected = array(
            array(0 => 1, 1 => 2, 2 => 3)
            , array(0 => 11, 1 => 12, 2 => 13)
            , array(0 => null, 1 => 1, 2 => null)
            , array(0 => null, 1 => 3, 2 => null)
            , array(0 => null, 1 => 4, 2 => null)
            , array(0 => null, 1 => 5, 2 => null)
            , array(0 => null, 1 => 6, 2 => null)
            , array(0 => null, 1 => 7, 2 => null)
            , array(0 => null, 1 => 8, 2 => null)
            , array(0 => null, 1 => 9, 2 => null)
            , array(0 => null, 1 => 10, 2 => null)
            , array(0 => null, 1 => 11, 2 => null)
            , array(0 => null, 1 => 13, 2 => null)
            , array(0 => null, 1 => 14, 2 => null)
            , array(0 => null, 1 => 15, 2 => null)
        );
        $expected = ArrayUtil::orderBy($expected, array(1));

        $this->assertEquals($expected, $actual);


        //
        $actual = ArrayUtil::toFillBlank(
            $ar,
            'id',
            [],
            array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp')
        );
        $this->assertEquals($ar, $actual);

        try {
            $actual = ArrayUtil::toFillBlank(
                [[]],
                'id',
                range(1, 10),
                array('name' => 'ZZZ', 'age' => 20, 'adr' => 'jp')
            );
            $this->fail("not trigger exception");
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function transverse($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $ar = array($a, $b, $c, $d);
        $expected = array(
            'name' => array('AAA', 'BBB', 'CCC', 'FFF')
            , 'age' => array(16, 22, 34, 22)
            , 'adr' => array('tokyo', 'osaka', 'kyoto', 'kyoto')
            , 'id' => array(2, 5, 7, 11)
        );

        $actual = ArrayUtil::transverse($ar);
        $this->assertEquals($expected, $actual);

        //
        $this->assertEquals(
            [[]],
            ArrayUtil::transverse([[]])
        );
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function unextractKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $keys = array('name', 'id');
        $actual = ArrayUtil::unextractKey($a, $keys);
        $this->assertEquals(array('age' => $a['age' ], 'adr' => $a['adr']), $actual);

        $keys = array(1, 2);
        $actual = ArrayUtil::unextractKey($x, $keys);
        $this->assertEquals(array(0 => $x[0]), $actual);

        //
        $keys = array('NAME', 'ID');
        $actual = ArrayUtil::unextractKey($a, $keys);
        $this->assertEquals($a, $actual);

        $actual = ArrayUtil::unextractKey($a, []);
        $this->assertEquals($a, $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function unselectBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = ArrayUtil::unselectBy($ar, array('id', 'name'));
        $expected = array(
            array('age' => 16,'adr' => 'tokyo')
            , array('age' => 22,'adr' => 'osaka')
            , array('age' => 34,'adr' => 'kyoto')
            , array('age' => 22,'adr' => 'kyoto')
        );

        $this->assertEquals($expected, $actual);


        $actual = ArrayUtil::unselectBy($br, array(2, 0));
        $expected = array(
            array(1 => 2)
            , array(1 => 12)
            , array(1 => 102)
        );
        $this->assertEquals($expected, $actual);

        //
        $actual = ArrayUtil::unselectBy($ar, []);
        $this->assertEquals($ar, $actual);

        $actual = ArrayUtil::unselectBy([[]], array('id', 'name'));
        $this->assertEquals([[]], $actual);
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function without($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $data = array(1,2,3,3,2,1,0);
        $expect = array(1,2,2,1);
        $this->assertEquals($expect, ArrayUtil::without($data, array(0,3), false));

        $data = array('a' => 1,  'b' => 2,  'c' => 3,  'd' => 3,  'e' => 2,  'f' => 1, 'g' => 0);
        $expect = array(1,2,2,1);
        $this->assertEquals($expect, ArrayUtil::without($data, array(0,3), false));

        $expect = array('a' => 1,  'b' => 2,  'e' => 2,  'f' => 1);
        $this->assertEquals($expect, ArrayUtil::without($data, array(0,3), true));

        //
        $this->assertEquals($data, ArrayUtil::without($data, [], true));

        $this->assertEquals([], ArrayUtil::without([], array(0,3), false));
    }
}
