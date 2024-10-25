<?php

declare(strict_types=1);

namespace test\Concerto\win;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\win\FileSystemObject;

class FileSystemObjectTest extends ConcertoTestCase
{
    private $tmp;


    public static function setUpBeforeClass(): void
    {
        exec(__DIR__ . '\\_set.bat');
    }

    public static function tearDownAfterClass(): void
    {
        // exec(__DIR__ . '\\_reset.bat');
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
        $this->tmp = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
    }

    /**
    */
    #[Test]
    public function dir()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        chdir($this->tmp);
        $obj = new FileSystemObject();

        $expect = [
            //$this->tmp . DIRECTORY_SEPARATOR . '空' . DIRECTORY_SEPARATOR,
            $this->tmp . DIRECTORY_SEPARATOR . '表示器' . DIRECTORY_SEPARATOR,
            $this->tmp . DIRECTORY_SEPARATOR . 'ccc.txt',
            $this->tmp . DIRECTORY_SEPARATOR . 'readme.txt',
            $this->tmp . DIRECTORY_SEPARATOR . '漢字2.txt',
            $this->tmp . DIRECTORY_SEPARATOR . '表示2.txt',
        ];

        sort($expect);
        $actual = $obj->dir();
        sort($actual);
        $actual2 = $obj->dir($this->tmp);
        sort($actual2);

        $this->assertEquals($expect, $actual);
        $this->assertEquals($expect, $actual2);
    }

    /**
    */
    #[Test]
    public function recursiveDir()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        chdir($this->tmp);
        $obj = new FileSystemObject();

        $expect = [
            //$this->tmp . DIRECTORY_SEPARATOR . '空' . DIRECTORY_SEPARATOR,
            //$this->tmp . DIRECTORY_SEPARATOR . '空' . DIRECTORY_SEPARATOR . '空' . DIRECTORY_SEPARATOR,
            $this->tmp . DIRECTORY_SEPARATOR . '表示器' . DIRECTORY_SEPARATOR,
            $this->tmp . DIRECTORY_SEPARATOR . '表示器' . DIRECTORY_SEPARATOR . 'test1.txt',
            $this->tmp . DIRECTORY_SEPARATOR . 'ccc.txt',
            $this->tmp . DIRECTORY_SEPARATOR . 'readme.txt',
            $this->tmp . DIRECTORY_SEPARATOR . '漢字2.txt',
            $this->tmp . DIRECTORY_SEPARATOR . '表示2.txt',
        ];

        sort($expect);
        $actual = $obj->recursiveDir();
        sort($actual);
        $actual2 = $obj->recursiveDir($this->tmp);
        sort($actual2);

        $this->assertEquals($expect, $actual);
        $this->assertEquals($expect, $actual2);
    }
}
