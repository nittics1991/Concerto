<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\FileOperation;
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


        FileOperationTest::$tmp = __DIR__ .
            DIRECTORY_SEPARATOR .
            'tmp';

        if (!file_exists(FileOperationTest::$tmp)) {
            mkdir(FileOperationTest::$tmp,0777,true);
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
    */
    #[Test]
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

        mkdir($temp_dir,0777,true);
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
}
