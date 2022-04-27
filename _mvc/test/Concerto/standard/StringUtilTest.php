<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use Concerto\standard\StringUtil;

class StringUtilTest extends ConcertoTestCase
{
    public function setUp(): void
    {
    }

    /**
    *   @test
    *
    */
    public function jsonFormating()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = <<< EEE
{
    "aa": "AAA",
    "bb": [
        1,
        2,
        3
    ]
}
EEE;

        $actual = [
            'aa' => 'AAA',
            'bb' => [1, 2, 3]
        ];

        $aaa = StringUtil::jsonFormating(json_encode($actual));
        $this->assertEquals($expect, StringUtil::jsonFormating(json_encode($actual)));
    }

    /**
    *   @test
    *
    */
    public function strToArray()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = 'aCd漢字12';
        $expect = ['a', 'C', 'd', '漢', '字', '1', '2'];
        $this->assertEquals($expect, StringUtil::strToArray($data));
    }

    /**
    *   @test
    *
    */
    public function escapeJavascript()
    {
//      $this->markTestIncomplete($data);

        // chr()では正しいUTF-8の128以上の文字を生成できないのでmb_decode_numericentity()を利用
        $convmap = [0x0000, 0xffff, 0, 0xffff];

        //0x00-0x2f
        $str = '';
        $expect = [];
        for ($i = 0x00; $i <= 0x2f; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
            $expect[] = $i;
        }

        $result = StringUtil::escapeJavascript($str);
        $actual = explode('\x', $result);
        array_shift($actual);
        array_walk($actual, function (&$val) {
            $str = "0x{$val}";
            $val = hexdec($str);
        });
        $this->assertEquals($expect, $actual);

        //数値
        $str = '';
        for ($i = 0x30; $i <= 0x39; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
        }

        $expect = implode('', range(0, 9));
        $actual = StringUtil::escapeJavascript($str);
        $this->assertEquals($expect, $actual);

        //0x3a-0x40
        $str = '';
        $expect = [];
        for ($i = 0x3a; $i <= 0x40; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
            $expect[] = $i;
        }

        $result = StringUtil::escapeJavascript($str);
        $actual = explode('\x', $result);
        array_shift($actual);
        array_walk($actual, function (&$val) {
            $str = "0x{$val}";
            $val = hexdec($str);
        });
        $this->assertEquals($expect, $actual);

        //大文字
        $str = '';
        $expect = '';
        for ($i = 0x41; $i <= 0x5a; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
            $expect .= chr($i);
        }

        $actual = StringUtil::escapeJavascript($str);
        $this->assertEquals($expect, $actual);

        //0x5b-0x60
        $str = '';
        $expect = [];
        for ($i = 0x5b; $i <= 0x60; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
            $expect[] = $i;
        }

        $result = StringUtil::escapeJavascript($str);
        $actual = explode('\x', $result);
        array_shift($actual);
        array_walk($actual, function (&$val) {
            $str = "0x{$val}";
            $val = hexdec($str);
        });
        $this->assertEquals($expect, $actual);

        //小文字
        $str = '';
        $expect = '';
        for ($i = 0x61; $i <= 0x7a; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
            $expect .= chr($i);
        }

        $actual = StringUtil::escapeJavascript($str);
        $this->assertEquals($expect, $actual);


        //0x7b-0xff
        $str = '';
        $expect = [];
        for ($i = 0x7b; $i <= 0xff; $i++) {
            $str .= mb_decode_numericentity("&#{$i};", $convmap, 'UTF8');
            $expect[] = $i;
        }

        $result = StringUtil::escapeJavascript($str);
        $actual = explode('\x', $result);
        array_shift($actual);
        array_walk($actual, function (&$val) {
            $str = "0x{$val}";
            $val = hexdec($str);
        });
        $this->assertEquals($expect, $actual);

        //仮名漢字など
        $str = $expect = 'かなカタカナ漢字（”）';
        $actual = StringUtil::escapeJavascript($str);
        $this->assertEquals($expect, $actual);
    }

    /**
    *   @test
    *
    */
    public function strToCode()
    {
//      $this->markTestIncomplete($data);

        $data = "文#字ｱ\ra1２";
        $expect = ['e69687', '23', 'e5ad97', 'efbdb1', '0d', '61', '31', 'efbc92'];
        $this->assertEquals($expect, StringUtil::strToCode($data));
    }

    /**
    *   @test
    *
    */
    public function codeToStr()
    {
//      $this->markTestIncomplete($data);

        $data = ['e69687', '23', 'e5ad97', 'efbdb1', '0d', '61', '31', 'efbc92'];
        $expect = "文#字ｱ\ra1２";
        $this->assertEquals($expect, StringUtil::codeToStr($data));
    }

    public function tokenProvider()
    {
        return [
            [
                "abc def  ghi   jkl
mno　pqr",
                ['abc', 'def', 'ghi', 'jkl', 'mno', 'pqr']
            ],
            ["", []],
        ];
    }

    /**
    *   @test
    *   @dataProvider tokenProvider
    *
    */
    public function token($data, $expect)
    {
//      $this->markTestIncomplete($data);

        $this->assertEquals($expect, StringUtil::token($data));
    }
}
