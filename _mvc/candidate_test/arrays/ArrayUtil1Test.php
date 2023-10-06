<?php

declare(strict_types=1);

namespace candidate_test\arrays;

use test\Concerto\ConcertoTestCase;
use candidate_test\arrays\StubArrayUtil;

class ArrayUtil1Test extends ConcertoTestCase
{
    public static function datatableProvider()
    {

        $a = ['name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2];
        $b = ['name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5];
        $c = ['name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7];
        $d = ['name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11];

        $ar = [$a, $b, $c, $d];

        $x = [1, 2, 3];
        $y = [11, 12, 13];
        $z = [101, 102, 103];

        $br = [$x, $y, $z];

        return [
            [$a, $b, $c, $d, $x, $y, $z, $ar, $br]
        ];
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function alignKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = StubArrayUtil::alignKey([$a, $b, $c, $d]);
        $expected = [
            ['name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2, 'aaa' => null, 'bbb' => null]
            , ['name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5, 'aaa' => null, 'bbb' => null]
            , ['name' => null,'age' => 34,'adr' => null, 'id' => 7, 'aaa' => null, 'bbb' => null]
            , ['name' => null,'age' => 22,'adr' => null, 'id' => null, 'aaa' => 'AAA', 'bbb' => 'BBB']
        ];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::alignKey([$x, $y]);
        $expected = [
            [0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => 1, 7 => 2, 8 => 3]
            , [0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null, 6 => 11, 7 => 12, 8 => 13]
        ];
        $this->assertEquals($expected, $actual);

        //
        $this->assertEquals([[]], StubArrayUtil::alignKey([[]]));
    }

    public static function arrayKeyData()
    {

        $a = ['name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2];
        $b = ['id' => 5, 'adr' => 'osaka','age' => 22, 'name' => 'BBB'];
        $c = ['age' => 34, 'id' => 7];
        $d = ['aaa' => 'AAA', 'age' => 22, 'bbb' => 'BBB'];

        $x = [1, 2, 3];
        $y = [11, 12, 13];

        return [
            [$a, $b, $c, $d, $x, $y]
        ];
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function expansion($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = StubArrayUtil::expansion($ar, 'name', 'id');
        $expected = ['AAA' => 2, 'BBB' => 5, 'CCC' => 7, 'FFF' => 11];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::expansion($br, 1, 0);
        $expected = [2 => 1, 12 => 11, 102 => 101];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::expansion($ar, 'name', 'id', function ($keys, $vals) {
            $result = [];
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
        $expected = ['AAA' => 2, 'BBB' => 7, 'CCC' => 14, 'FFF' => 25];
        $this->assertEquals($expected, $actual);

        //
        try {
            $actual = StubArrayUtil::expansion([[]], 'name', 'id');
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

        $keys = ['name', 'id'];
        $actual = StubArrayUtil::extractKey($a, $keys);
        $this->assertEquals(['name' => $a['name' ], 'id' => $a['id']], $actual);

        $keys = [1, 2];
        $actual = StubArrayUtil::extractKey($x, $keys);
        $this->assertEquals([1 => $x[1], 2 => $x[2]], $actual);

        //
        $keys = ['NAME', 'ID'];
        $actual = StubArrayUtil::extractKey($a, $keys);
        $this->assertEquals(['NAME' => null, 'ID' => null], $actual);

        $keys = ['name', 'id'];
        $actual = StubArrayUtil::extractKey([], $keys);
        $this->assertEquals(['name' => null, 'id' => null], $actual);

        $actual = StubArrayUtil::extractKey($a, []);
        $this->assertEquals([], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function groupBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = StubArrayUtil::groupBy(
            $ar,
            ['age'],
            ['id'
                     => function ($array) {
                        return array_sum($array);
                     }
                ]
        );

        $expected = [
            ['age' => 16, 'id' => 2]
            , ['age' => 22, 'id' => 16]
            , ['age' => 34, 'id' => 7]
        ];
        $this->assertEquals($expected, $actual);


        $actual = StubArrayUtil::groupBy(
            $br,
            [1],
            [0 => 'array_sum']
        );

        $expected = [
            [2, 1]
            , [12, 11]
            , [102, 101]
        ];
        $this->assertEquals($expected, $actual);

        $a = ['name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2];
        $b = ['name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5];
        $c = ['name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7];
        $d = ['name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11];
        $e = ['name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 15];
        $ar =  [$a, $b, $c, $d, $e];


        $actual = StubArrayUtil::groupBy(
            $ar,
            ['age', 'name'],
            ['id'
                     => function ($array) {
                        return array_sum($array);
                     }
                ]
        );

        $expected = [
            ['age' => 16, 'name' => 'AAA', 'id' => 2]
            , ['age' => 22, 'name' => 'BBB', 'id' => 20]
            , ['age' => 22, 'name' => 'FFF', 'id' => 11]
            , ['age' => 34, 'name' => 'CCC', 'id' => 7]
        ];
        $this->assertEquals($expected, $actual);

        //
        try {
            $actual = StubArrayUtil::groupBy(
                [[]],
                ['age'],
                ['id'
                        => function ($array) {
                            return array_sum($array);
                        }
                ]
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

        $actual = StubArrayUtil::isDimension($a, 1);
        $this->assertEquals(true, $actual);

        $actual = StubArrayUtil::isDimension([$a, $b]);
        $this->assertEquals(true, $actual);

        $actual = StubArrayUtil::isDimension([$a, $b], 3);
        $this->assertEquals(false, $actual);

        $actual = StubArrayUtil::isDimension([$a, 'aaa', $b]);
        $this->assertEquals(false, $actual);

        //
        $actual = StubArrayUtil::isDimension(
            [
                [
                    [$a]
                ]
            ],
            3
        );
        $this->assertEquals(true, $actual);

        $actual = StubArrayUtil::isDimension([], 1);
        $this->assertEquals(true, $actual);
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function mergeKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = StubArrayUtil::mergeKey($d, $b, $a, $c);
        ksort($actual);
        $expected = ['aaa' => null, 'adr' => null, 'age' => null, 'bbb' => null, 'id' => null, 'name' => null];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::mergeKey($a, $b);
        ksort($actual);
        $expected = ['adr' => null, 'age' => null, 'id' => null, 'name' => null];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::mergeKey($x, $y);
        ksort($actual);
        $expected = [0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null];
        $this->assertEquals($expected, $actual);

        //
        $actual = StubArrayUtil::mergeKey($x, []);
        $expected = [0 => null, 1 => null, 2 => null];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::mergeKey([], $x);
        $expected = [0 => null, 1 => null, 2 => null];
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
        $actual = StubArrayUtil::mergeKey([], 'AA');
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function mergeKeyArray($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = StubArrayUtil::mergeKeyArray([$d, $b, $a, $c]);
        ksort($actual);
        $expected = ['aaa' => null, 'adr' => null, 'age' => null, 'bbb' => null, 'id' => null, 'name' => null];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::mergeKeyArray([$a, $b]);
        ksort($actual);
        $expected = ['adr' => null, 'age' => null, 'id' => null, 'name' => null];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::mergeKeyArray([$x, $y]);
        ksort($actual);
        $expected = [0 => null, 1 => null, 2 => null, 3 => null, 4 => null, 5 => null];
        $this->assertEquals($expected, $actual);

        //
        $actual = StubArrayUtil::mergeKeyArray([$x, []]);
        $expected = [0 => null, 1 => null, 2 => null];
        $this->assertEquals($expected, $actual);

        $actual = StubArrayUtil::mergeKeyArray([[], $x]);
        $expected = [0 => null, 1 => null, 2 => null];
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
        $actual = StubArrayUtil::mergeKeyArray([1, 2, 3]);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function orderBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = StubArrayUtil::orderBy($ar, ['age', 'name'], [SORT_DESC , SORT_ASC ], null);
        $expected = [
            ['name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7]
            , ['name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5]
            , ['name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11]
            , ['name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2]
        ];
        $this->assertEquals($expected, $actual);


        $actual = StubArrayUtil::orderBy($br, [0], [SORT_DESC], null);
        $expected = [
            [101, 102, 103]
            , [11, 12, 13]
            , [1, 2, 3]
        ];
        $this->assertEquals($expected, $actual);

        //
        $actual = StubArrayUtil::orderBy(
            [[]],
            ['age', 'name'],
            [SORT_DESC , SORT_ASC ],
            null
        );
        $this->assertEquals([[]], $actual);
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function positionKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $actual = StubArrayUtil::positionKey($a, 'age');
        $this->assertEquals(1, $actual);

        $actual = StubArrayUtil::positionKey($x, 2);
        $this->assertEquals(2, $actual);

        $actual = StubArrayUtil::positionKey($x, 5);
        $this->assertEquals(false, $actual);

        //
        $actual = StubArrayUtil::positionKey($a, 'DUMMY');
        $this->assertEquals(false, $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function rotate($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {

        $actual = StubArrayUtil::rotate($x, 1);
        $this->assertEquals([2, 3, 1], $actual);

        $actual = StubArrayUtil::rotate($x, 2);
        $this->assertEquals([3, 1, 2], $actual);

        $actual = StubArrayUtil::rotate($x, -1);
        $this->assertEquals([3, 1, 2], $actual);

        $actual = StubArrayUtil::rotate($a, -1);
        $this->assertEquals(['adr' => 'tokyo', 'id' => 2, 'name' => 'AAA','age' => 16], $actual);

        //
        $actual = StubArrayUtil::rotate([], 1);
        $this->assertEquals([], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function stepwise($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {

        $actual = StubArrayUtil::stepwise($x, function ($val1, $val2) {
                return $val1 + $val2;
        });
        $this->assertEquals([1, 3, 6], $actual);

        $actual = StubArrayUtil::stepwise($y, function ($val1, $val2) {
                return $val1 + $val2;
        });
        $this->assertEquals([11, 23, 36], $actual);

        $actual = StubArrayUtil::stepwise($x, function ($val1, $val2) {
                return $val1 * $val2;
        }, 10);
        $this->assertEquals([10, 20, 60], $actual);

        //
        $actual = StubArrayUtil::stepwise([], function ($val1, $val2) {
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
        $actual = StubArrayUtil::selectBy($ar, ['id', 'name']);
        $expected = [
            ['id' => 2, 'name' => 'AAA']
            , ['id' => 5, 'name' => 'BBB']
            , ['id' => 7, 'name' => 'CCC']
            , ['id' => 11, 'name' => 'FFF']
        ];

        $this->assertEquals($expected, $actual);


        $actual = StubArrayUtil::selectBy($br, [2, 0]);
        $expected = [
            [2 => 3, 0 => 1]
            , [2 => 13, 0 => 11]
            , [2 => 103, 0 => 101]
        ];
        $this->assertEquals($expected, $actual);

        //
        $actual = StubArrayUtil::selectBy($ar, []);
        $this->assertEquals([[]], $actual);

        $actual = StubArrayUtil::selectBy([[]], ['id', 'name']);
        $this->assertEquals([[]], $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function selectBy2($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = StubArrayUtil::selectBy($ar, ['id', 'DMY', 'name']);
        $expected = [
            ['id' => 2, 'DMY' => null, 'name' => 'AAA']
            , ['id' => 5, 'DMY' => null, 'name' => 'BBB']
            , ['id' => 7, 'DMY' => null, 'name' => 'CCC']
            , ['id' => 11, 'DMY' => null, 'name' => 'FFF']
        ];
        $this->assertEquals($expected, $actual);


        $actual = StubArrayUtil::selectBy($ar, ['id', 'name', 'DMY'], ['DMY' => 'INIT', 'id' => 'X']);
        $expected = [
            ['id' => 2, 'name' => 'AAA', 'DMY' => 'INIT']
            , ['id' => 5, 'name' => 'BBB', 'DMY' => 'INIT']
            , ['id' => 7, 'name' => 'CCC', 'DMY' => 'INIT']
            , ['id' => 11, 'name' => 'FFF', 'DMY' => 'INIT']
        ];
        $this->assertEquals($expected, $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function toFillBlank($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = StubArrayUtil::toFillBlank(
            $ar,
            'id',
            range(1, 10),
            ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp']
        );
        $actual = StubArrayUtil::orderBy($actual, ['id']);

        $expected = [
            ['name' => 'AAA','age' => 16,'adr' => 'tokyo', 'id' => 2]
            , ['name' => 'BBB','age' => 22,'adr' => 'osaka', 'id' => 5]
            , ['name' => 'CCC','age' => 34,'adr' => 'kyoto', 'id' => 7]
            , ['name' => 'FFF','age' => 22,'adr' => 'kyoto', 'id' => 11]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 1]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 3]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 4]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 6]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 8]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 9]
            , ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp', 'id' => 10]
        ];
        $expected = StubArrayUtil::orderBy($expected, ['id']);

        $this->assertEquals($expected, $actual);

        //
        $actual = StubArrayUtil::toFillBlank(
            [$x, $y],
            1,
            range(1, 15)
        );
        $actual = StubArrayUtil::orderBy($actual, [1]);

        $expected = [
            [0 => 1, 1 => 2, 2 => 3]
            , [0 => 11, 1 => 12, 2 => 13]
            , [0 => null, 1 => 1, 2 => null]
            , [0 => null, 1 => 3, 2 => null]
            , [0 => null, 1 => 4, 2 => null]
            , [0 => null, 1 => 5, 2 => null]
            , [0 => null, 1 => 6, 2 => null]
            , [0 => null, 1 => 7, 2 => null]
            , [0 => null, 1 => 8, 2 => null]
            , [0 => null, 1 => 9, 2 => null]
            , [0 => null, 1 => 10, 2 => null]
            , [0 => null, 1 => 11, 2 => null]
            , [0 => null, 1 => 13, 2 => null]
            , [0 => null, 1 => 14, 2 => null]
            , [0 => null, 1 => 15, 2 => null]
        ];
        $expected = StubArrayUtil::orderBy($expected, [1]);

        $this->assertEquals($expected, $actual);


        //
        $actual = StubArrayUtil::toFillBlank(
            $ar,
            'id',
            [],
            ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp']
        );
        $this->assertEquals($ar, $actual);

        try {
            $actual = StubArrayUtil::toFillBlank(
                [[]],
                'id',
                range(1, 10),
                ['name' => 'ZZZ', 'age' => 20, 'adr' => 'jp']
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

        $ar = [$a, $b, $c, $d];
        $expected = [
            'name' => ['AAA', 'BBB', 'CCC', 'FFF']
            , 'age' => [16, 22, 34, 22]
            , 'adr' => ['tokyo', 'osaka', 'kyoto', 'kyoto']
            , 'id' => [2, 5, 7, 11]
        ];

        $actual = StubArrayUtil::transverse($ar);
        $this->assertEquals($expected, $actual);

        //
        $this->assertEquals(
            [[]],
            StubArrayUtil::transverse([[]])
        );
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function unextractKey($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $keys = ['name', 'id'];
        $actual = StubArrayUtil::unextractKey($a, $keys);
        $this->assertEquals(['age' => $a['age' ], 'adr' => $a['adr']], $actual);

        $keys = [1, 2];
        $actual = StubArrayUtil::unextractKey($x, $keys);
        $this->assertEquals([0 => $x[0]], $actual);

        //
        $keys = ['NAME', 'ID'];
        $actual = StubArrayUtil::unextractKey($a, $keys);
        $this->assertEquals($a, $actual);

        $actual = StubArrayUtil::unextractKey($a, []);
        $this->assertEquals($a, $actual);
    }

    /**
    * @test
    * @dataProvider datatableProvider
    */
    public function unselectBy($a, $b, $c, $d, $x, $y, $z, $ar, $br)
    {
        $actual = StubArrayUtil::unselectBy($ar, ['id', 'name']);
        $expected = [
            ['age' => 16,'adr' => 'tokyo'],
            ['age' => 22,'adr' => 'osaka'],
            ['age' => 34,'adr' => 'kyoto'],
            ['age' => 22,'adr' => 'kyoto'],
        ];

        $this->assertEquals($expected, $actual);


        $actual = StubArrayUtil::unselectBy($br, [2, 0]);
        $expected = [
            [1 => 2],
            [1 => 12],
            [1 => 102],
        ];
        $this->assertEquals($expected, $actual);

        //
        $actual = StubArrayUtil::unselectBy($ar, []);
        $this->assertEquals($ar, $actual);

        $actual = StubArrayUtil::unselectBy([[]], ['id', 'name']);
        $this->assertEquals([[]], $actual);
    }

    /**
    * @test
    * @dataProvider arrayKeyData
    */
    public function without($a, $b, $c, $d, $x, $y)
    {

//      $this->markTestIncomplete();

        $data = [1,2,3,3,2,1,0];
        $expect = [1,2,2,1];
        $this->assertEquals($expect, StubArrayUtil::without($data, [0,3], false));

        $data = ['a' => 1,  'b' => 2,  'c' => 3,  'd' => 3,  'e' => 2,  'f' => 1, 'g' => 0];
        $expect = [1,2,2,1];
        $this->assertEquals($expect, StubArrayUtil::without($data, [0,3], false));

        $expect = ['a' => 1,  'b' => 2,  'e' => 2,  'f' => 1];
        $this->assertEquals($expect, StubArrayUtil::without($data, [0,3], true));

        //
        $this->assertEquals($data, StubArrayUtil::without($data, [], true));

        $this->assertEquals([], StubArrayUtil::without([], [0,3], false));
    }
}
