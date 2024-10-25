<?php

declare(strict_types=1);

namespace test\Concerto\ROOT;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\Validate;
use Concerto\standard\StringUtil;

class ValidateTest extends ConcertoTestCase
{
////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   ASCII文字
    */

    public static function isSuccessAsciiProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['!!', null, null],
            ['00', 2, null],
            ['99', null, 3],
            ['AA', 2, 2],
            ['zz', null, null],
            ['~~', 1, 3],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessAsciiProvider')]
    public function testSuccessIsAscii($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isAscii($val));
    }

    public static function isFailureAsciiProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['0123', 5, null],
            ['0123', null, 3],
            ['0123', 1, 3],
            ['0123', 5, 4],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureAsciiProvider')]
    public function testFailureIsAscii($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isAscii($val, $min, $max));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   部門コード
    */
    public static function isSuccessBumonProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['PB123'],
            ['I0C12'],
            ['IBB12'],
            ['12345'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessBumonProvider')]
    public function testSuccessIsBumon($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isBumon($val));
    }

    public static function isFailureBumonProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['PB1'],
            ['I0C123'],
            [12345],
            [null],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureBumonProvider')]
    public function testFailureIsBumon($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isBumon($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   注番
    */
    public static function isSuccessCyubanProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['IBB12345'],
            ['I0C00A3'],
            ['ICH30123'],
            ['3PB1234'],
            ['LS18320'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessCyubanProvider')]
    public function testSuccessIsCyuban($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isCyuban($val));
    }

    public static function isFailureCyubanProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['IBB123450'],
            ['LS123'],
            ['IBB1-345'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureCyubanProvider')]
    public function testFaulureIsCyuban($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isCyuban($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   注文番号
    */
    public static function isSuccessCyumonProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['KIBB12345'],
            ['GI0C00837'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessCyumonProvider')]
    public function testSuccessIsCyumon($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isCyumon($val));
    }

    public static function isFailureCyumonProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['0IBB12345'],
            ['GI0C008379'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureCyumonProvider')]
    public function testFailureIsCyumon($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isCyumon($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   Email
    */
    public static function isEmailProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['namae.sei@mail.google.co.jp'],
            ['seimei@yahoo.com'],
            ['_.-!#$%&+*?@domain.co.jp'],
        ];
    }

    /**
    *
    */
    #[Test]
    #[DataProvider('isEmailProvider')]
    public function failureIsEmail($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isEmail($val));
    }

    /**
    *   Email
    */
    public static function isEmailProviderOK()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['namae.sei@toshiba.co.jp'],
            ['seimei@glb.toshiba.co.jp'],
            ['012adJN@toshiba.co.jp'],
            ['012adJN@d93.mail.toshiba'],
            ['012adJN@3ZA.mail.toshiba'],
        ];
    }

    /**
    *
    */
    #[Test]
    #[DataProvider('isEmailProviderOK')]
    public function isEmail($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isEmail($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   Email Text
    */
    public static function isEmailTextProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['namae.sei@mail.google.co.jp;itc123@gmail.com;123@gmail.com'],
            ['seimei@yahoo.com'],
            ['_.-!#$%&+*?@domain.co.jp'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isEmailTextProvider')]
    public function testIsEmailText($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isEmailText($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   浮動小数(isFloat, isDouble)
    */
    public static function isSuccessFloatProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [+100.3, null, null],
            [99.9, 0, 100],
            [1.01, 1, 100],
            [-1.01, -2, 0],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessFloatProvider')]
    public function testSuccessIsFloat($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isFloat($val, $min, $max));
    }

    public static function isFailureFloatProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [+100, 100.01, null],
            [100, null, 99],
            ['10', 0, 100],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureFloatProvider')]
    public function testFailureIsFloat($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isFloat($val, $min, $max));
    }

    //以下isDouble

    /**
    *
    *
    */
    #[DataProvider('isSuccessFloatProvider')]
    public function testSuccessIsDouble($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isDouble($val, $min, $max));
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureFloatProvider')]
    public function testFailureIsDouble($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isDouble($val, $min, $max));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   原価要素
    */
    public static function isSuccessGenkaYosoProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['A'],
            ['C1'],
            ['C'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessGenkaYosoProvider')]
    public function testSuccessIsGenkaYoso($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isGenkaYoso($val));
    }

    public static function isFailureGenkaYosoProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['a'],
            ['E'],
            ['A1'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureGenkaYosoProvider')]
    public function testFailureIsGenkaYoso($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isGenkaYoso($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   整数
    */
    public static function isSuccessIntProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [100, null, null],
            [100, 0, 100],
            [0, 0, 100],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessIntProvider')]
    public function testSuccessIsInt($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isInt($val, $min, $max));
    }

    public static function isFailureIntProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [101, 0, 100],
            [9, 10, 100],
            [-1, 0, 100],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureIntProvider')]
    public function testFailureIsInt($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isInt($val, $min, $max));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   項番
    */
    public static function isSuccessKobanProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['PB12'],
            ['CH12'],
            ['CF123'],
            ['3PB4'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessKobanProvider')]
    public function testSuccessIsKoban($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isKoban($val));
    }

    public static function isFailureKobanProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['PB1'],
            ['CF1235'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureKobanProvider')]
    public function testFailureIsKoban($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isKoban($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   数値(金額)
    */
    public function testIsMoney()
    {
        $this->assertTrue(Validate::isMoney('0'));
        $this->assertTrue(Validate::isMoney('1'));
        $this->assertTrue(Validate::isMoney('+1'));
        $this->assertTrue(Validate::isMoney('-1'));
        $this->assertTrue(Validate::isMoney('12'));
        $this->assertTrue(Validate::isMoney('+12'));
        $this->assertTrue(Validate::isMoney('-12'));
        $this->assertTrue(Validate::isMoney('123'));
        $this->assertTrue(Validate::isMoney('+123'));
        $this->assertTrue(Validate::isMoney('-123'));
        $this->assertTrue(Validate::isMoney('1234'));
        $this->assertTrue(Validate::isMoney('+1234'));
        $this->assertTrue(Validate::isMoney('-1234'));
        $this->assertTrue(Validate::isMoney('123.456789'));
        $this->assertTrue(Validate::isMoney('+123.456789'));
        $this->assertTrue(Validate::isMoney('-123.456789'));
        $this->assertTrue(Validate::isMoney('1234.56789'));
        $this->assertTrue(Validate::isMoney('+1234.56789'));
        $this->assertTrue(Validate::isMoney('-1234.56789'));
        $this->assertTrue(Validate::isMoney('1,234'));
        $this->assertTrue(Validate::isMoney('+1,234'));
        $this->assertTrue(Validate::isMoney('-1,234'));
        $this->assertTrue(Validate::isMoney('12,345'));
        $this->assertTrue(Validate::isMoney('+12,345'));
        $this->assertTrue(Validate::isMoney('-12,345'));
        $this->assertTrue(Validate::isMoney('123,456'));
        $this->assertTrue(Validate::isMoney('1,234,567'));
        $this->assertTrue(Validate::isMoney('+1,234,567'));
        $this->assertTrue(Validate::isMoney('-1,234,567'));
        $this->assertTrue(Validate::isMoney('1,234,567.89'));
        $this->assertTrue(Validate::isMoney('+1,234,567.89'));
        $this->assertTrue(Validate::isMoney('-1,234,567.89'));

        $this->assertFalse(Validate::isMoney('1,23'));
        $this->assertFalse(Validate::isMoney(',234'));
        $this->assertFalse(Validate::isMoney('1.'));
        $this->assertFalse(Validate::isMoney('.1'));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   年度コード
    */
    public static function isSuccessNendoProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['2014K'],
            ['2015S'],
            ['2000K'],
            ['2099S'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessNendoProvider')]
    public function testSuccessIsNendo($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isNendo($val));
    }

    public static function isFailureNendoProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['2014A'],
            ['2015'],
            ['2015SS'],
            ['A000K'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureNendoProvider')]
    public function testFailureIsNendo($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isNendo($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   社員番号
    */
    public static function isSuccessTantoProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['00000ITC'],
            ['99999ITC'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTantoProvider')]
    public function testSuccessIsTanto($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTanto($val));
    }

    public static function isFailureTantoProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['00000IT'],
            ['1234ITC'],
            ['X9999ITC'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTantoProvider')]
    public function testFailureIsTanto($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTanto($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   文字列
    */
    public static function isSuccessTextProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['aaa', null, null],
            ['漢字', 0, 100],
            ['abc', 3, 100],
            ['abc', 1, 3],
            ['ひらがな', 4, 4],
            ['カタカナ', 4, 4],
            ['ｶﾀｶﾅ', 4, 4],
            ['ｶﾀｶﾅﾀﾞﾊﾟ', 6, 6]   //半角カタカナ文字数
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextProvider')]
    public function testSuccessIsText($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isText($val, $min, $max));
    }

    public static function isFailureTextProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['aaa', null, 2],
            ['abc', 4, null],
            ['漢字', null, 1],
            ['漢字', 3, null],
            [123, 1, 3],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextProvider')]
    public function testFailureIsText($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isText($val, $min, $max));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   文字列フラグ
    */
    public static function isSuccessTextBoolProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['0'],
            ['1'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextBoolProvider')]
    public function testSuccessIsTextBool($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextBool($val));
    }

    public static function isFailureTextBoolProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            [0],
            [1],
            ['2'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextBoolProvider')]
    public function testFailureIsTextBool($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextDate($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   日付文字列yyyymmdd
    */
    public static function isSuccessTextDate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['20150131'],
            ['19800101'],
            ['29991231'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextDate')]
    public function testSuccessIsTextDate($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextDate($val));
    }

    public static function isFailureTextDate()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
             ['2015930'],
            ['201511221'],
            ['20150231'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextDate')]
    public function testFailureIsTextDate($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextDate($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   日付文字列yyyymmdd HHiiss
    */
    public static function isSuccessTextDateTime()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['20150131 000000'],
            ['19800101 235959'],
            ['20991231 123456'],
            ['29991231 123456'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextDateTime')]
    public function testSuccessIsTextDateTime($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextDateTime($val));
    }

    public static function isFailureTextDateTime()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['20150131 00000'],
            ['19800101 240000'],
            ['20150229 123456'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextDateTime')]
    public function testFailureIsTextDateTime($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextDateTime($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   日付文字列yyyymm
    */
    public static function isSuccessTextDateYYYYMM()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['201501'],
            ['199912'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextDateYYYYMM')]
    public function testSuccessIsTextDateYYYYMM($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextDateYYYYMM($val));
    }

    public static function isFailureTextDateYYYYMM()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['201500'],
            ['199913'],
            ['2014011'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextDateYYYYMM')]
    public function testFailureIsTextDateYYYYMM($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextDateYYYYMM($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   日付文字列yyyy-mm-dd
    */
    public static function isSuccessTextDateYYYYMMDDHyphen()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['2015-01-29'],
            ['1999-12-31'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextDateYYYYMMDDHyphen')]
    public function testSuccessIsTextDateYYYYMMDDHyphen($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextDateYYYYMMDDHyphen($val));
    }

    public static function isFailureTextDateYYYYMMDDHyphen()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['2015-00-01'],
            ['1999-13-01'],
            ['20140131'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextDateYYYYMMDDHyphen')]
    public function testFailureIsTextDateYYYYMMDDHyphen($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextDateYYYYMMDDHyphen($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   文字列浮動小数
    */
    public static function isSuccessTextFloatProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['1.05', null, null, null],
            ['-10.3', -20, 100, null],
            ['+1.33', 1, 1.5, null],
            ['99.11', 99.1, 99.2, 2],
            ['99.1234567', 99.1, 99.2, 8],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextFloatProvider')]
    public function testSuccessIsTextFloat($val, $min, $max, $scale)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextFloat($val, $min, $max, $scale));
    }

    public static function isFailureTextFloatProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['105', null, null, null],
            ['10.0', 11, 100, null],
            ['10.0', 1, 9.9, null],
            ['99.11', 99.1, 99.2, 1],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextFloatProvider')]
    public function testFailureIsTextFloat($val, $min, $max, $scale)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextFloat($val, $min, $max, $scale));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   文字列整数
    */
    public static function isSuccessTextIntProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['105', null, null],
            ['10', 10, 100],
            ['100', 10, 100],
            ['+11', null, null],
            ['-11', null, null],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessTextIntProvider')]
    public function testSuccessIsTextInt($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isTextInt($val, $min, $max));
    }

    public static function isFailureTextIntProvider()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['99', 100, null],
            ['101', 10, 100],
            [101, 10, 100],
            [2.2, null, null],
            ["2.2", 1, 3],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureTextIntProvider')]
    public function testFailureIsTextInt($val, $min, $max)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isTextInt($val, $min, $max));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   統一ユーザID
    */
    public static function isSuccessUser()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['w12345iC'],
            ['12345678'],
            // ['ZA123'],
            // ['Z1234'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessUser')]
    public function testSuccessIsUser($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isUser($val));
    }

    public static function isFailureUser()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['w12345iCx'],
            ['1234567'],
            ['w_2345iC'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureUser')]
    public function testFailureIsUser($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isUser($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *   URL
    */
    public static function isSuccessUrl()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['https://www.toshiba.co.jp:8080/test.htm?key=AAA&val=BBB'],
            ['https://toshiba/'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isSuccessUrl')]
    public function testSuccessIsUrl($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertTrue(Validate::isUrl($val));
    }

    public static function isFailureUrl()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        return [
            ['ftp://www.toshiba.co.jp:8080/test.htm?key=AAA&val=BBB'],
            ['http://東芝/'],
        ];
    }

    /**
    *
    *
    */
    #[DataProvider('isFailureUrl')]
    public function testFailureIsUrl($val)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertFalse(Validate::isUrl($val));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function isTextEscape()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //英数漢字許可
        $data = '01234asdfghjkQWERTY漢字許可';
        $this->assertTrue(Validate::isTextEscape($data));

        //英数漢字許可 記号不許可
        $data = '01234asdfghjkQWERTY漢字@';
        $this->assertFalse(Validate::isTextEscape($data));

        //英数漢字許可 記号不許可
        $data = '01234!asdfghjk{QWERTY漢字}@';
        $this->assertFalse(Validate::isTextEscape($data, null, null));

        //英数漢字許可 記号不許可
        $data = '01234!asdfghjk{QWERTY漢字}@';
        $this->assertFalse(Validate::isTextEscape($data, null, null, ''));

        //英数漢字許可 指定記号許可
        $data = '01234!asdfghjk{QWERTY漢字}@';
        $this->assertTrue(Validate::isTextEscape($data, null, null, '@!}{%'));

        //英数漢字許可 指定記号許可 SP \t不許可
        $data = '012    34!asd fghjk{QWERTY漢字}@';
        $this->assertFalse(Validate::isTextEscape($data, null, null, '@!}{%'));

        //英数漢字許可 指定記号 SP \t許可
        $data = '012    34!asd fghjk{QWERTY漢字}@';
        $this->assertTrue(Validate::isTextEscape($data, null, null, '@!} {\t%'));

        //英数漢字許可 記号許可
        $data = '01234!asdfghjk{QWERTY漢字}@';
        $this->assertTrue(Validate::isTextEscape($data, null, null, null, ''));

        //英数漢字許可 指定記号不許可
        $data = '01234!asdfghjk {QWERTY漢字}@';
        $this->assertFalse(Validate::isTextEscape($data, null, null, null, '@!}{%'));

        //英数漢字許可 指定記号以外許可
        $data = '01234$asdfghjk)QWERTY(?';
        $this->assertTrue(Validate::isTextEscape($data, null, null, null, '@!}{%'));

        //英数漢字許可 指定記号 \t以外許可
        $data   =   '01234$asdf	ghjk)QWERTY漢字(?';
        $this->assertFalse(Validate::isTextEscape($data, null, null, null, '@!}{%\t'));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function isTextHiragana()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = 'あおんぁぽが';
        $this->assertTrue(Validate::isTextHiragana($data));

        $data = 'aあおんぁぽが';
        $this->assertFalse(Validate::isTextHiragana($data));

        $data = 'ら';
        $this->assertFalse(Validate::isTextHiragana($data, 2));

        $data = 'っきゅ';
        $this->assertFalse(Validate::isTextHiragana($data, null, 2));

        $data = 'ちぃぐぜ';
        $this->assertTrue(Validate::isTextHiragana($data, 0, 4));

        $data = '';
        $this->assertTrue(Validate::isTextHiragana($data, 0, 4));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function isTextKatakana()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = 'アオンァポガ';
        $this->assertTrue(Validate::isTextKatakana($data));

        $data = 'aアオンァポガ';
        $this->assertFalse(Validate::isTextKatakana($data));

        $data = 'ラ';
        $this->assertFalse(Validate::isTextKatakana($data, 2));

        $data = 'ッキュ';
        $this->assertFalse(Validate::isTextKatakana($data, null, 2));

        $data = 'チィグゼ';
        $this->assertTrue(Validate::isTextKatakana($data, 0, 4));

        $data = '';
        $this->assertTrue(Validate::isTextKatakana($data, 0, 4));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function isTextHankaku()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = 'ｱｵﾝｧﾎﾟｶﾞ';
        $this->assertTrue(Validate::isTextHankaku($data));

        $data = 'aｱｵﾝｧﾎﾟｶﾞ';
        $this->assertFalse(Validate::isTextHankaku($data));

        $data = 'ﾗ';
        $this->assertFalse(Validate::isTextHankaku($data, 2));

        $data = 'ｯｷｭ';
        $this->assertFalse(Validate::isTextHankaku($data, null, 2));

        $data = 'ﾁｨｸﾞｾﾞ';
        $this->assertTrue(Validate::isTextHankaku($data, 0, 4));

        $data = '';
        $this->assertTrue(Validate::isTextHankaku($data, 0, 4));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function hasTextDatabase()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = '文%字ｱa1２';
        $this->assertTrue(Validate::hasTextDatabase($data));

        $data = 'a1２文字';
        $this->assertFalse(Validate::hasTextDatabase($data));

        $expect = ['%', '_', "'", '"'];

        foreach ($expect as $val) {
            $this->assertTrue(Validate::hasTextDatabase($val));
        }
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function hasTextHankaku()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = '文#字ｱa1２';
        $this->assertTrue(Validate::hasTextHankaku($data));

        $data = '文#字ﾝa1２';
        $this->assertTrue(Validate::hasTextHankaku($data));

        $data = 'a1２文字';
        $this->assertFalse(Validate::hasTextHankaku($data));
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function hasTextHtml()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = '文<字ｱa1２';
        $this->assertTrue(Validate::hasTextHtml($data));

        $data = 'a1２文字';
        $this->assertFalse(Validate::hasTextHtml($data));

        $expect = ['<', '>', '&', "'", '"'];

        foreach ($expect as $val) {
            $this->assertTrue(Validate::hasTextHtml($val));
        }
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
    *
    */
    #[Test]
    public function hasTextSymbole()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $data = '文#字ｱa1２';
        $this->assertTrue(Validate::hasTextSymbole($data));

        $data = 'ｱa1２文字';
        $this->assertFalse(Validate::hasTextSymbole($data));

        $expect = ['!', '"', '#', '$', '%', '&'     , "'", '(', ')'
            , '-', '=', '^', '~', '\\', '|'
            , '@', '`', '[', '{'
            , ';', '+', ':', '*', ']', '}'
            , ',', '<', '.', '?', '\\', '_'
        ];

        foreach ($expect as $val) {
            $this->assertTrue(Validate::hasTextSymbole($val));
        }
    }

////////////////////////////////////////////////////////////////////////////////////////////////////
    public static function isFilenameProvider()
    {
        return [
            [
                "09Az漢字ひらがなｶﾀｶﾅ!#$%&'()=-~^@`[{]}+,._",
                true
            ],
            ['/Az', false],
            ['A\z', false],
            ['Az<', false],
            ['Az>', false],
            ['Az*', false],
            ['Az?', false],
            ['Az"', false],
            ['Az|', false],
            ['Az;', false],
            ['Az:', false],
            [str_repeat('x', 107), true],
            [str_repeat('x', 108), false],

        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isFilenameProvider')]
    public function isFilename($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isFilename($data));
    }

    public static function isTextTimeProvider()
    {
        return [
            ['000000', true],
            ['235959', true],
            ['240000', false],
            ['006000', false],
            ['000060', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isTextTimeProvider')]
    public function isTextTime($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isTextTime($data));
    }

    public static function isTextTimeHHIIProvider()
    {
        return [
            ['0000', true],
            ['2359', true],
            ['2400', false],
            ['0060', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isTextTimeHHIIProvider')]
    public function isTextTimeHHII($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isTextTimeHHII($data));
    }

    public static function isColorProvider()
    {
        return [
            ['#000000', true],
            ['#999999', true],
            ['#aaaaaa', true],
            ['#ffffff', true],
            ['#AAAAAA', true],
            ['#FFFFFF', true],
            ['#00000g', false],
            ['#12345', false],
            ['#1234567', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isColorProvider')]
    public function isColor($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isColor($data));
    }

    public static function isMitumoriProvider()
    {
        return [
            ['12345678', true],            [12345678, false],
            ['1234567', false],            ['F1234567', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isMitumoriProvider')]
    public function isMitumori($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isMitumori($data));
    }

    public static function isIpv4Provider()
    {
        return [
            ['10.43.19.104', true],            ['192.168.0.256', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isIpv4Provider')]
    public function isIpv4($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isIpv4($data));
    }

    public static function isIpv6Provider()
    {
        return [
            ['FF01:0:0:0:0:0:0:101', true],            ['FF01::101', true],
            ['192.168.0.256', false],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isIpv6Provider')]
    public function isIpv6($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals($expect, Validate::isIpv6($data));
    }
}
