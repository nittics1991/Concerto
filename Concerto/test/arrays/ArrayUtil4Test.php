<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\ArrayUtil;

class ArrayUtil4Test extends ConcertoTestCase
{
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
}
