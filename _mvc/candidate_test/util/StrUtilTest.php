<?php

declare(strict_types=1);

namespace dev_test\array;

use test\Concerto\ConcertoTestCase;
use candidate\util\StrUtil;
use RuntimeException;

class StrUtilTest extends ConcertoTestCase
{
    public function snakeProvider()
    {
        return [
            [
                'studyCaseString',
                'study_case_string',
            ],
            [
                'CamelCaseString',
                'camel_case_string',
            ],
            [
                '_snake_case_string',
                'snake_case_string',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider snakeProvider
    */
    public function snake(
        string $string,
        string $expect,
    ) {
//      $this->markTestIncomplete();

        $this->assertSame(
            $expect,
            StrUtil::snake($string),
        );
    }
    
    public function callStaticProvider()
    {
        return [
            //mb_‚ÌŒã‚ë1’PŒê
            [
                'substr',
                [
                    'abcdefgh',
                    2,
                    3,
                ],
                'cde',
            ],
            //mb_‚ÌŒã‚ë2’PŒê
            [
                'eregMatch',
                [
                    '.+bbb',
                    'aaabbbaaaccc',
                ],
                true,
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider callStaticProvider
    */
    public function callStatic(
        string $name,
        array $arguments,
        mixed $expect,
    )
    {
//      $this->markTestIncomplete();

        $this->assertSame(
            $expect,
            call_user_func_array(
                StrUtil::class . "::{$name}",
                $arguments,
            ),
        );
    }
    
    public function errorJudgementProvider()
    {
        return [
            //OK
            [
                'chr',
                [
                    65,
                ],
                'A',
            ],
            //NG
            [
                'chr',
                [
                    65,
                ],
                false,  //error
            ],
        ];
    }
    
    /**
    *   @test
    *   @dataProvider errorJudgementProvider
    */
    public function errorJudgement(
        string $name,
        array $arguments,
        mixed $expect,
    )
    {
//      $this->markTestIncomplete();

        if ($expect) {
            $this->assertSame(
                $expect,
                call_user_func_array(
                    StrUtil::class . "::{$name}",
                    $arguments,
                ),
            );
        } else {
            try {
                call_user_func_array(
                    StrUtil::class . "::{$name}",
                    $arguments,
                );
                
                $this->assertSame(1, 0);
            } catch (RuntimeException $e) {
                $this->assertSame(1, 1);
            }
        }
    }
}
