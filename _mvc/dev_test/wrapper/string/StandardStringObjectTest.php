<?php

declare(strict_types=1);

namespace test\Concerto\wrapper\string;

use PHPUnit\Framework\TestCase;
use test\Concerto\PrivateTestTrait;
use Concerto\wrapper\string\StandardStringObject;

class StandardStringObjectTest extends TestCase
{
    use PrivateTestTrait;

    protected function setUp(): void
    {
    }

    public function validEncodeNameProvider()
    {
        return [
            //OK mb_list_encodings
            [
                'UTF-8',
                true,
            ],
            [
                'SJIS',
                true,
            ],
            //NG mb_list_encodings
            [
                'UTF',
                false,
            ],
            //OK mb_encoding_aliases
            [
                'us',
                true,
            ],
            //NG mb_encoding_aliases
            [
                'US',
                false,
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider validEncodeNameProvider
    */
    public function validEncodeName(
        string $string,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new StandardStringObject(
            '',
        );

        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'validEncodeName',
                [$string,],
            ),
        );
    }

    public function constructProvider()
    {
        return [
            //ASCII mb_list_encodings
            [
                'alphabet string 123',
                'SJIS',
            ],
            //ASCII mb_encoding_aliases
            [
                'alphabet string 123',
                'us',
            ],
            //multibyte mb_list_encodings
            [
                '漢字　文字列',
                'SJIS',
            ],
            //multibyte mb_encoding_aliases
            [
                '漢字　文字列',
                'us',
            ],
            //制御コード
            [
                "漢字　文字列
                    改行含む",
                'UTF-8',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function construct(
        string $string,
        string $encoding = 'UTF-8',
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = new StandardStringObject(
            $string,
            $encoding,
        );

        $this->assertEquals(
            $string,
            $this->getPrivateProperty(
                $obj,
                'string',
            ),
        );

        $this->assertEquals(
            $encoding,
            $this->getPrivateProperty(
                $obj,
                'encoding',
            ),
        );
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function create(
        string $string,
        string $encoding = 'UTF-8',
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');

        $obj = StandardStringObject::create(
            $string,
            $encoding,
        );

        $this->assertEquals(
            $string,
            $this->getPrivateProperty(
                $obj,
                'string',
            ),
        );

        $this->assertEquals(
            $encoding,
            $this->getPrivateProperty(
                $obj,
                'encoding',
            ),
        );
    }

    public function toStringProvider()
    {
        return [
            //default charset mb_list_encodings
            [
                'alphabet string 123',
                (string)ini_get('default_charset'),
                'alphabet string 123',
            ],
            //ASCII mb_list_encodings
            [
                'alphabet string 123',
                'SJIS',
                'alphabet string 123',
            ],
            //ASCII mb_encoding_aliases
            [
                'alphabet string 123',
                'us',
                'alphabet string 123',
            ],
            //multibyte mb_list_encodings
            [
                '漢字　文字列',
                'SJIS',
                '漢字　文字列',
            ],
            //multibyte mb_encoding_aliases
            [
                '漢字　文字列',
                'us',
                '漢字　文字列',
            ],
            //制御コード
            [
                "漢字　文字列
                    改行含む",
                'UTF-8',
                "漢字　文字列
                    改行含む",
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider toStringProvider
    */
    public function toString1(
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
            $obj->toString(),
        );
    }
}
