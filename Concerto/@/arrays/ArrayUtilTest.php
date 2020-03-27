<?php

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\ArrayUtil;

class ArrayUtilTest extends ConcertoTestCase
{
    /**
    *
    *   @test
    **/
    public function compere()
    {
        $src = array(
            'database' => array (
                'default' => array(
                    'adapter' => 'pgsql',
                    'params' => array(
                        'host' => 'localhost',
                        'port' => '5432',
                        'dbname' => 'postgres',
                        'user' => 'postgres',
                        'password' => 'manager'
                    )
                )
            )
            , 'log' => array(
                'default' => array(
                    'stream' => 'err.log',
                    'format' => '%s:%s' . PHP_EOL
                )
            )
        );
        
        $target = $src;
        
        $this->assertEquals(array(), ArrayUtil::compare($target, $src));
        
        
        $target['log']['default']['stream'] = 'CHANGE';
        $target['database']['default']['params']['user'] = 'AAAA';
        
        $expect['log']['default']['stream'] = array('CHANGE', 'err.log');
        $expect['database']['default']['params']['user'] = array('AAAA', 'postgres');
        
        $this->assertEquals($expect, ArrayUtil::compare($target, $src));
    }
    
    /**
    *
    *   @test
    **/
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
    **/
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
    **/
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
    **/
    public function min()
    {
//      $this->markTestIncomplete();
        
        $data = array(11, 2, 3, 3.14, 16, 4);
        $this->assertEquals(2, ArrayUtil::min($data));
        
        $data = array(11, 2, 3, 3.14, 16, 'A', false, true);
        $this->assertEquals(false, ArrayUtil::min($data));
        
        $data = array();
        $this->assertEquals(null, ArrayUtil::min($data));
        
        $data = array('D', 'A', 'C', 'AA', 'B');
        $this->assertEquals('A', ArrayUtil::min($data, SORT_NATURAL));
        
        $this->assertEquals(null, ArrayUtil::min([], SORT_NATURAL));
    }
    
    /**
    *
    *   @test
    **/
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
    **/
    public function last()
    {
//      $this->markTestIncomplete();
        
        $data = array(11, 2, 3, 3.14, 16, 4);
        $this->assertEquals(4, ArrayUtil::last($data));
        
        $data = array();
        $this->assertEquals(null, ArrayUtil::last($data));
    }
    
    /**
    *   テーブルテストデータ
    *
    **/
    public function providerTable()
    {
        return array(
        array(array(
          '山田' => array(
              'ID' => '001',
              '出身' => '函館',
              'メールアドレス' => 'yamada@example.com',
              '性別' => '女性'
          ),
          '田中' => array(
              'ID' => '002',
              '出身' => '仙台',
              'メールアドレス' => 'tanaka@example.com',
              '性別'  => '男性'
          ),
          '高橋' => array(
              'ID' => '003',
              '出身' => '札幌',
              'メールアドレス' => 'takahasi@example.com',
              '性別'  => '女性',
          ),
          '井上' => array(
              'ID' => '004',
              '出身' => '東京',
              'メールアドレス' => 'inoue@example.com',
              '性別'  => '男性',
          ),
          '小林' => array(
              'ID' => '005',
              '出身' => '大阪',
              'メールアドレス' => 'kobayasi@example.com',
              '性別'  => '男性',
          ),
          '森' => array(
              'ID' => '006',
              '出身' => '沖縄',
              'メールアドレス' => 'mori@example.com',
              '性別'  => '女性',
          )
        ))
        );
    }
    
    /**
    *
    *   @test
    *   @dataProvider providerTable
    **/
    public function sameStruct($data)
    {
//      $this->markTestIncomplete();
        
        $ar1 = $data['山田'];
        $ar2 = $data['森'];
        $this->assertEquals(true, ArrayUtil::sameStruct($ar1, $ar2));
        
        //データ型変更
        $ar2['ID'] = 111;
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
        
        //ID変更
        $ar2 = $data['森'];
        unset($ar2['ID']);
        $ar2['id'] = '111';
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
        
        //カラム多
        $ar2 = $data['森'];
        $ar2['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
        
        //カラム少
        $ar2 = $data['森'];
        unset($ar2['性別']);
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
        
        //順序不同
        $ar2 = $data['森'];
        $ar2 = ArrayUtil::rotate($ar2);
        $this->assertEquals(false, ArrayUtil::sameStruct($ar1, $ar2));
    }
    
    
    /**
    *
    *   @test
    *   @dataProvider providerTable
    **/
    public function sameKeys($data)
    {
//      $this->markTestIncomplete();
        
        $ar1 = $data['山田'];
        $ar2 = $data['森'];
        $this->assertEquals(true, ArrayUtil::sameKeys($ar1, $ar2));
        
        //データ型変更
        $ar2['ID'] = 111;
        $this->assertEquals(true, ArrayUtil::sameKeys($ar1, $ar2));
        
        //ID変更
        $ar2 = $data['森'];
        unset($ar2['ID']);
        $ar2['id'] = '111';
        $this->assertEquals(false, ArrayUtil::sameKeys($ar1, $ar2));
        
        //カラム多
        $ar2 = $data['森'];
        $ar2['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::sameKeys($ar1, $ar2));
        
        //カラム少
        $ar2 = $data['森'];
        unset($ar2['性別']);
        $this->assertEquals(false, ArrayUtil::sameKeys($ar1, $ar2));
        
        //順序不同
        $ar2 = $data['森'];
        $ar2 = ArrayUtil::rotate($ar2);
        $this->assertEquals(true, ArrayUtil::sameKeys($ar1, $ar2));
    }
    
    /**
    *
    *   @test
    *   @dataProvider providerTable
    **/
    public function isTable($data)
    {
//      $this->markTestIncomplete();
        
        $target = $data;
        $this->assertEquals(true, ArrayUtil::isTable($target, true, true));
        $this->assertEquals(true, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(true, ArrayUtil::isTable($target, false, false));
        
        //データ型変更
        $target = $data;
        $target['森']['ID'] = 111;
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(true, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(true, ArrayUtil::isTable($target, false, false));
        
        //ID変更
        $target = $data;
        unset($target['森']['性別']);
        $target['森']['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(false, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(true, ArrayUtil::isTable($target, false, false));
        
        //カラム多
        $target = $data;
        $target['森']['変更'] = '111';
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(false, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(false, ArrayUtil::isTable($target, false, false));
        
        //カラム少
        $target = $data;
        unset($target['森']['性別']);
        $this->assertEquals(false, ArrayUtil::isTable($target));    //true, true
        $this->assertEquals(false, ArrayUtil::isTable($target, true, false));
        $this->assertEquals(false, ArrayUtil::isTable($target, false, false));
    }
    
    /**
    *   TABLE JOIN provider
    *
    **/
    public function providerJoinTable()
    {
        return array(
            array(
                array(
                    array(1, '0401', 103),
                    array(2, '0402', 101),
                    array(3, '0403', 101),
                    array(4, '0403', 102),
                    array(5, '0404', 104)
                ),
                array(
                    array(101, 'AA'),
                    array(102, 'BB'),
                    array(103, 'CC')
                )
            )
        );
    }
    
    /**
    *
    *   @test
    *   @dataProvider providerJoinTable
    **/
    public function joinTable($ar1, $ar2)
    {
//      $this->markTestIncomplete();
        
        //on条件なし
        $actual = ArrayUtil::joinTable($ar1, $ar2, array(), 'LEFT', '_');
        $expect = array(
            array(1, '0401', 103, '0_' => 101, '1_' => 'AA'),
            array(1, '0401', 103, '0_' => 102, '1_' => 'BB'),
            array(1, '0401', 103, '0_' => 103, '1_' => 'CC'),
            
            array(2, '0402', 101, '0_' => 101, '1_' => 'AA'),
            array(2, '0402', 101, '0_' => 102, '1_' => 'BB'),
            array(2, '0402', 101, '0_' => 103, '1_' => 'CC'),
            
            array(3, '0403', 101, '0_' => 101, '1_' => 'AA'),
            array(3, '0403', 101, '0_' => 102, '1_' => 'BB'),
            array(3, '0403', 101, '0_' => 103, '1_' => 'CC'),
            
            array(4, '0403', 102, '0_' => 101, '1_' => 'AA'),
            array(4, '0403', 102, '0_' => 102, '1_' => 'BB'),
            array(4, '0403', 102, '0_' => 103, '1_' => 'CC'),
            
            array(5, '0404', 104, '0_' => 101, '1_' => 'AA'),
            array(5, '0404', 104, '0_' => 102, '1_' => 'BB'),
            array(5, '0404', 104, '0_' => 103, '1_' => 'CC')
        );
        
        $this->assertEquals($expect, $actual);
        
        //suffixなし
        //添字番号のmergeなのでカラムを上書きしない(array_merge)
        $actual = ArrayUtil::joinTable($ar1, $ar2, array(), 'LEFT');
        $expect = array(
            array(1, '0401', 103, 101, 'AA'),
            array(1, '0401', 103, 102, 'BB'),
            array(1, '0401', 103, 103, 'CC'),
            
            array(2, '0402', 101, 101, 'AA'),
            array(2, '0402', 101, 102, 'BB'),
            array(2, '0402', 101, 103, 'CC'),
            
            array(3, '0403', 101, 101, 'AA'),
            array(3, '0403', 101, 102, 'BB'),
            array(3, '0403', 101, 103, 'CC'),
            
            array(4, '0403', 102, 101, 'AA'),
            array(4, '0403', 102, 102, 'BB'),
            array(4, '0403', 102, 103, 'CC'),
            
            array(5, '0404', 104, 101, 'AA'),
            array(5, '0404', 104, 102, 'BB'),
            array(5, '0404', 104, 103, 'CC')
        );
        
        $this->assertEquals($expect, $actual);
        
        //innerでjoinしても同じ
        $actual = ArrayUtil::joinTable($ar1, $ar2, array(), 'inner');
        $this->assertEquals($expect, $actual);
        
        //on条件付加
        $actual = ArrayUtil::joinTable($ar1, $ar2, array(array(2, 0)), 'left');
        $expect = array(
            array(1, '0401', 103, 103, 'CC'),
            array(2, '0402', 101, 101, 'AA'),
            array(3, '0403', 101, 101, 'AA'),
            array(4, '0403', 102, 102, 'BB'),
            array(5, '0404', 104, null, null)
        );
        
        $this->assertEquals($expect, $actual);
        
        $actual = ArrayUtil::joinTable($ar1, $ar2, array(array(2, 0)), 'inner');
        $expect = array(
            array(1, '0401', 103, 103, 'CC'),
            array(2, '0402', 101, 101, 'AA'),
            array(3, '0403', 101, 101, 'AA'),
            array(4, '0403', 102, 102, 'BB')
        );
        
        $this->assertEquals($expect, $actual);
    }
    
    /**
    *
    *   @test
    **/
    public function mergeKeepKey()
    {
//      $this->markTestIncomplete();
        
        $x = [
            'AA' => 'aa',   //残
            'BB' => 'bb',   //残
            '012' => 012,   //残
            012 => '8進',  //キーを8進数=>10進数
            345 => 345,     //キー10進数の結果上書きされる
            014 => '8進2'  //キーを8進数=>10進数の結果10は上書きされる
        ];
        
        $y = [
            0,              //残
            1,              //残
            '345' => 'new',     //残
            12 => 'ZZZ',    //残
            '012' => 'max',     //残
            13,                 //キーを数値キー最大+1
            14              //キーを数値キー最大+2
        ];
        
        $expect = [
            'AA' => 'aa',   //x
            'BB' => 'bb',   //x
            '012' => 'max',     //y
            10 => '8進',   //x
            345 => 'new',   //y
            12 => 'ZZZ',    //y
            0 => 0,             //y
            1 => 1,             //y
            346 => 13,      //y
            347 => 14       //y
        ];
        $this->assertEquals($expect, ArrayUtil::mergeKeepKey($x, $y));
    }
    
    public function keyRemapProvider()
    {
        return [
            [
                [
                    'tanto_code' => '12345',
                    'tanto_name' => 'ABC',
                    'bumon_code' => 2,
                    'bumon_name' => 'XXX'
                ],
                ['tanto_code', 'bumon_code'],
                ['cd_tanto', 'cd_bumon'],
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider keyRemapProvider
    **/
    public function keyRemap($data, $serKey, $destKey)
    {
//      $this->markTestIncomplete();
        
        $expect = [
            'cd_tanto' => '12345',
            'cd_bumon' => 2
        ];
        
        $this->assertEquals($expect, ArrayUtil::keyRemap($data, $serKey, $destKey));
    }
    
    /**
    *   @test
    *   @dataProvider keyRemapProvider
    **/
    public function keyPertiallyRemap($data, $serKey, $destKey)
    {
//      $this->markTestIncomplete();
        
        $expect = [
            'cd_tanto' => '12345',
            'tanto_name' => 'ABC',
            'cd_bumon' => 2,
            'bumon_name' => 'XXX'
        ];
        
        $this->assertEquals($expect, ArrayUtil::keyPartiallyRemap($data, $serKey, $destKey));
    }
    
    public function replaceWithoutProvider()
    {
        return [
            [
                [null, '', 0],
                [
                    [1,2,3,4,5,6,7,8,9,0],
                    [0,4,7,9,5,3,1,6,8,2],
                    [9,8,7,6,5,4,3,2,1,0]
                ],
                [9,8,7,6,5,4,3,2,1,2]
            ],
            [
                [null],
                [
                    [1,2,3,4,5],
                    [9,null,7,6,5,4,3]
                ],
                [9,2,7,6,5,4,3]
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider replaceWithoutProvider
    **/
    public function replaceWithout($exclude, $dataset, $expect)
    {
//      $this->markTestIncomplete();
        
        array_unshift($dataset, $exclude);
        $actual = forward_static_call_array(
            ['Concerto\standard\ArrayUtil', 'replaceWithout'],
            $dataset
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function replaceInitParamProvider()
    {
        return [
            [
                [
                    ['a' => 1, 'b' => 2, 'c' => 3, 4, 5],
                    ['x'  => 11, 'a' => 12, 'Y' => 13, 'c' => 14],
                    ['c' => 21, 22, 'y' => 23, '1' => 24],
                ],
                ['a' => 12, 'b' => 2, 'c' => 21, 22, 24]
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider replaceInitParamProvider
    **/
    public function replaceInitParam($dataset, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'replaceInitParam'],
            $dataset
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function someProvider()
    {
        return [
            [
                [1, 'A', '2'],
                function ($key, $val) {
                    return is_int($val);
                },
                true
            ],
            [
                ['a', 'A', 'x'],
                function ($key, $val) {
                    return is_int($val);
                },
                false
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider someProvider
    **/
    public function some($array, $collback, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'some'],
            [$array, $collback]
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function everyProvider()
    {
        return [
            [
                [1, 3, 2],
                function ($key, $val) {
                    return is_int($val);
                },
                true
            ],
            [
                [1, '2', 2],
                function ($key, $val) {
                    return is_int($val);
                },
                false
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider everyProvider
    **/
    public function every($array, $collback, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'every'],
            [$array, $collback]
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function flattenProvider()
    {
        return [
            [
                [
                    'a' => [1, 2, 3],
                    'b' => [11, 12],
                    'c' => [21, 22, 23],
                ],
                1,
                [1, 2, 3, 11, 12, 21, 22, 23]
            ],
            [
                [
                    'a' => [
                        'aa' => [1, 2, 3],
                        'ab' => [11, 12],
                    ],
                    'b' => [
                        'ba' => [21, 22],
                        'bb' => [31, 32, 33],
                    ],
                ],
                1,
                [
                    0 => [1, 2, 3],
                    1 => [11, 12],
                    2 => [21, 22],
                    3 => [31, 32, 33],
                ],
            ],
            [
                [
                    'a' => [1, 2, 3],
                    'b' => [11, 12],
                    'c' => [21, 22, 23],
                ],
                2,
                [1, 2, 3, 11, 12, 21, 22, 23]
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider flattenProvider
    **/
    public function flatten($array, $depth, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'flatten'],
            [$array, $depth]
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function isEmptyTableProvider()
    {
        return [
            [
                [],
                true
            ],
            [
                [0, false, [null], []],
                true
            ],
            [
                [0, false, [null], [0, 1]],
                false
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider isEmptyTableProvider
    **/
    public function isEmptyTable($array, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'isEmptyTable'],
            [$array]
        );
        $this->assertEquals($expect, $actual);
    }
    
    public function makeColumnFromRowProvider()
    {
        return [
            [
                [
                    ['A' => 10, 'B' => 400, 'C' => 1],
                    ['A' => 20, 'B' => 300, 'C' => 2],
                    ['A' => 30, 'B' => 200, 'C' => 3],
                    ['A' => 40, 'B' => 100, 'C' => 4],
                ],
                function ($row) {
                    return [
                        'AB' => $row['A'] + $row['B'],
                        'AC' => $row['A'] + $row['C'],
                    ];
                },
                [
                    ['AB' => 410, 'AC' => 11],
                    ['AB' => 320, 'AC' => 22],
                    ['AB' => 230, 'AC' => 33],
                    ['AB' => 140, 'AC' => 44],
                ],
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider makeColumnFromRowProvider
    **/
    public function makeColumnFromRow($array, $callback, $expect)
    {
//      $this->markTestIncomplete();
        
        $actual = call_user_func_array(
            ['Concerto\standard\ArrayUtil', 'makeColumnFromRow'],
            [$array, $callback]
        );
        $this->assertEquals($expect, $actual);
    }
}
