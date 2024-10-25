<?php

declare(strict_types=1);

namespace test\Concerto\mbstring;

use SplFileObject;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\mbstring\MbString;

class MbStringFileTest extends ConcertoTestCase
{
    private $dir;

    protected function setUp(): void
    {
        if (
            !isset($_SERVER["OS"]) ||
            stripos($_SERVER["OS"], 'WINDOWS') === false
        ) {
            $this->markTestSkipped('Windows上でのみテスト実行');
            return;
        }

         $this->dir = __DIR__ . '\\tmp';

        if (!file_exists($this->dir)) {
            mkdir($this->dir);
        }
    }

//  public static function tearDownAfterClass()
    protected function tearDown(): void
    {
        exec("del /Q {$this->dir}\\*.* ");
    }

    /**
    *   ファイル操作
    *
    */
    #[Test]
    public function fileOperation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = "{$this->dir}\\テスト表示.txt";
        $sjis = mb_convert_encoding(MbString::escape5c($file), 'SJIS-WIN', 'UTF8');
        $expect = mb_convert_encoding($file, 'SJIS-WIN', 'UTF8');

        $data = mb_convert_encoding("文字列\r\n表示\r\n文字列", 'SJIS-WIN', 'UTF8');

        file_put_contents($sjis, $data, LOCK_EX);
        $this->assertFileExists($expect);

        $actual = file_get_contents($sjis);
        $this->assertEquals($data, $actual);

        $file2 = "{$this->dir}\\テスト予定.txt";
        $sjis2 = mb_convert_encoding(MbString::escape5c($file2), 'SJIS-WIN', 'UTF8');
        $expect2 = mb_convert_encoding($file2, 'SJIS-WIN', 'UTF8');

        copy($sjis, $sjis2);
        $this->assertFileExists($expect2);

        //renameは5c処理不要

        unlink($expect2);
        $this->assertFileDoesNotExist($expect2);

        //mkdirは5c処理不要
        //rmdirは5c処理不要
    }
    /**
    *   SplFileObject
    *
    */
    #[Test]
    public function splFileObject()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = "{$this->dir}\\テスト表示.txt";
        $sjis = mb_convert_encoding(MbString::escape5c($file), 'SJIS-WIN', 'UTF8');
        $expect = mb_convert_encoding($file, 'SJIS-WIN', 'UTF8');

        $splFileObject = new SplFileObject($sjis, 'w');

        $data = mb_convert_encoding("文字列\r\n表示\r\n文字列", 'SJIS-WIN', 'UTF8');

        $splFileObject->fwrite($data);
        $splFileObject = null;
        $this->assertFileExists($expect);


        $splFileObject = new SplFileObject($sjis, 'r');

        $actual1 = $splFileObject->fgets();
        $actual2 = $splFileObject->fgets();
        $splFileObject = null;

        $expect1 = mb_convert_encoding("文字列\r\n", 'SJIS-WIN', 'UTF8');
        $expect2 = mb_convert_encoding("表示\r\n", 'SJIS-WIN', 'UTF8');

        $this->assertEquals($expect1, $actual1);
        $this->assertEquals($expect2, $actual2);
    }
}
