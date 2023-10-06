<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\string;

use PHPUnit\Framework\TestCase;
use test\Concerto\PrivateTestTrait;
use Concerto\wrapper\string\StandardStringObject;

class StandardStringObjectCaseTest extends TestCase
{
    use PrivateTestTrait;

    protected function setUp(): void
    {
    }

    public function lowerProvider()
    {
        return [
            //ASCII default charset
            [
                mb_convert_encoding(
                    'aLPHABEt STRING 123',
                    'UTF-8',
                ),
                'UTF-8',
                mb_convert_encoding(
                    'alphabet string 123',
                    'UTF-8',
                ),
            ],
            //multibyte default charset
            [
                mb_convert_encoding(
                    "ａＬＰＨＡＢＥｔ ＳＴＲＩＮＧ　１２３",
                    'UTF-8',
                ),
                'UTF-8',
                mb_convert_encoding(
                    "ａｌｐｈａｂｅｔ ｓｔｒｉｎｇ　１２３",
                    'UTF-8',
                ),
            ],
            //multibyte another encoding
            [
                mb_convert_encoding(
                    "ａｌｐｈａｂｅｔ ｓｔｒｉｎｇ　１２３",
                    'SJIS',
                ),
                'SJIS',
                mb_convert_encoding(
                    "ａｌｐｈａｂｅｔ ｓｔｒｉｎｇ　１２３",
                    'SJIS',
                ),
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider lowerProvider
    */
    public function lower(
        string $string,
        string $encoding = 'UTF-8',
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new StandardStringObject(
            $string,
            $encoding,
        );

        $this->assertEquals(
            $expect,
            $obj->lower()->toString(),
        );
    }




}
