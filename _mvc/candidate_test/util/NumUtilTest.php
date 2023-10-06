<?php

declare(strict_types=1);

namespace dev_test\array;

use test\Concerto\ConcertoTestCase;
use candidate\util\NumUtil;

class NumUtilTest extends ConcertoTestCase
{
    public function divProvider()
    {
        return [
            //int,int,int,int
            [
                12,
                4,
                0,
                3,
            ],
            //int,int,int,float
            [
                15,
                4,
                0,
                3.75,
            ],
            //int,0,int,int
            [
                12,
                4,
                3,
                3,
            ],
            //float,float,float,float
            [
                12.4,
                4.0,
                3.0,
                3.1,
            ],
            //float,0.0,float,float
            [
                12.4,
                0.0,
                3.0,
                3.0,
            ],
            //string_int,string_int,string_int,int
            [
                '12',
                '4',
                '0',
                3,
            ],
            //string_float,string,string_int,float
            [
                '12.4',
                '4',
                '0',
                3.1,
            ],
            //string_int,'0',string_float,string_float
            [
                '12',
                '0',
                '3.1',
                '3.1',
            ],
            //int,float,int,float
            [
                12,
                4.0,
                0,
                3.0,
            ],
            //int,string_int,int,int
            [
                12,
                '4',
                0,
                3,
            ],
            //float,string_float,int,float
            [
                12.0,
                '4.0',
                0,
                3.0,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider divProvider
    */
    public function div(
        int|float|string $num1,
        int|float|string $num2,
        int|float|string $default,
        int|float|string $expect,
    ) {
//      $this->markTestIncomplete();

        $this->assertSame(
            $expect,
            NumUtil::div(
                $num1,
                $num2,
                $default,
            ),
        );
    }
}
