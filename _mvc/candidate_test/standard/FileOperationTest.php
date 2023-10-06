<?php

declare(strict_types=1);

namespace candidate_test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use candidate\standard\FileOperation;
use Concerto\mbstring\MbString;

class FileOperationTest extends ConcertoTestCase
{
    private static $tmp;

    public static function setUpBeforeClass(): void
    {
        /*
        *   touch()とかvfsStreamでは使えないので実ファイルシステムで実行する
        *   @see https://github.com/bovigo/vfsStream/wiki/Known-Issues
        */

        FileOperationTest::$tmp = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';

        if (!file_exists(FileOperationTest::$tmp)) {
            mkdir(FileOperationTest::$tmp);
        }
    }

    public static function tearDownAfterClass(): void
    {
        FileOperationTest::deleteRecurciveFileSystem(FileOperationTest::$tmp);
        rmdir(FileOperationTest::$tmp);
    }

    public function setUp(): void
    {
        if (
            !isset($_SERVER["OS"]) ||
            stripos($_SERVER["OS"], 'WINDOWS') === false
        ) {
            $this->markTestSkipped('Windows上でのみテスト実行');
            return;
        }
        FileOperationTest::deleteRecurciveFileSystem(FileOperationTest::$tmp);
    }

    /**
    *   recurcive dir cleanup
    *
    *   @param string $path
    */
    public static function deleteRecurciveFileSystem(string $path)
    {
        $dir = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \FilesystemIterator::SKIP_DOTS
            ),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($dir as $fileInfo) {
            if ($fileInfo->isFile()) {
                unlink($fileInfo->getRealPath());
            } else {
                rmdir($fileInfo->getRealPath());
            }
        }
    }

    /**
    * @test
    */
    public function SuccessStandard()
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        //copy
        $obj = new FileOperation(false);

        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'aaa.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'bbb.txt';
        file_put_contents($src, '漢字を表示する');

        $obj->copy($src, $dest);
        $this->assertFileEquals($src, $dest);


        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '漢字.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '漢字1.txt';

        //php7.1
        // $src1    = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'auto'));
        // $dest1   = MbString::escape5c(mb_convert_encoding($dest, 'SJIS', 'auto'));
        $src1   = $src;
        $dest1  = $dest;

        file_put_contents($src1, '漢字を表示する');

        $obj->copy($src, $dest);
        $this->assertFileEquals($src1, $dest1);

        //5c文字対応
        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '表示.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '表示1.txt';

        //php7.1
        // $src1    = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'UTF8'));
        // $dest1   = MbString::escape5c(mb_convert_encoding($dest, 'SJIS', 'UTF8'));
        $src1   = $src;
        $dest1  = $dest;

        exec("echo 漢字を表示する > {$src1}");

        $obj->copy($src, $dest);
        exec("fc {$src1} {$dest1}", $ans);

        $this->assertFileEquals($src1, $dest1);

        //delete
        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'aaa.txt';
        $obj->delete($src);
        $this->assertFileDoesNotExist($src);

        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '漢字.txt';
        $src1   = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'UTF8'));
        $obj->delete($src);
        $this->assertFileDoesNotExist($src);

        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '表示.txt';
        $src1   = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'UTF8'));
        $obj->delete($src);
        $this->assertFileDoesNotExist($src);



        //rename
        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'bbb.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'ccc.txt';
        $obj->rename($src, $dest);
        $this->assertFileExists($dest);

        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '漢字1.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '漢字2.txt';

        //php7.1
        // $dest1   = MbString::escape5c(mb_convert_encoding($dest, 'SJIS', 'UTF8'));
        $dest1  = $dest;

        $obj->rename($src, $dest);
        $this->assertFileExists($dest1);

        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '表示1.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . '表示2.txt';

        //php7.1
        // $dest1   = MbString::escape5c(mb_convert_encoding($dest, 'SJIS', 'UTF8'));
        $dest1  = $dest;

        $obj->rename($src, $dest);
        $this->assertFileExists($dest1);
    }

    /**
    * @test
    */
    public function ExceptionCopy()
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $obj = new FileOperation();
        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'zzz.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'yyy.txt';
        $obj->copy($src, $dest);
    }

    /**
    * @test
    */
    public function ExceptionDelete()
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $obj = new FileOperation();
        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'zzz.txt';
        $obj->delete($src);
    }

    /**
    * @test
    */
    public function ExceptionRename()
    {
     // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $obj = new FileOperation();
        $src    = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'zzz.txt';
        $dest   = FileOperationTest::$tmp . DIRECTORY_SEPARATOR . 'yyy.txt';
        $obj->rename($src, $dest);
    }

    /**
    * @test
    */
    public function clearTempDir()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $temp_dir = FileOperationTest::$tmp .
            DIRECTORY_SEPARATOR .
            'ctd';

        $inner_file_name = implode(
            DIRECTORY_SEPARATOR,
            [
                $temp_dir,
                'test1.txt',
            ],
        );

        mkdir($temp_dir);
        touch($inner_file_name, strtotime('-1 day'));

        $obj = new FileOperation();

        $obj->clearTempDir($temp_dir, 2);
        $this->assertEquals(
            true,
            file_exists($inner_file_name)
        );

        $obj->clearTempDir($temp_dir, 1);
        $this->assertEquals(
            false,
            file_exists($inner_file_name)
        );
    }

    /**
    * @test
    */
    public function createTempDir()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $temp_dir = FileOperationTest::$tmp .
            DIRECTORY_SEPARATOR .
            'ctd';

        $inner_file_name = implode(
            DIRECTORY_SEPARATOR,
            [
                $temp_dir,
                'test1.txt',
            ],
        );

        $obj = new FileOperation();

        $obj->createTempDir($temp_dir, 2);
        $this->assertEquals(
            true,
            file_exists($temp_dir)
        );

        touch($inner_file_name, strtotime('-1 day'));

        $obj->createTempDir($temp_dir, 2);
        $this->assertEquals(
            true,
            file_exists($inner_file_name)
        );

        FileOperationTest::deleteRecurciveFileSystem($temp_dir);

        $obj->createTempDir($temp_dir, 1);
        $this->assertEquals(
            false,
            file_exists($inner_file_name)
        );
    }
}
