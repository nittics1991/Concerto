<?php

declare(strict_types=1);

namespace test\Concerto\filesystem;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    PrivateTestTrait,
};
use Concerto\filesystem\Filesystem;
use test\Concerto\filesystem\StubFilesystem;

class FilesystemTest extends TestCase
{
    use PrivateTestTrait;
    
    protected function setUp(): void
    {
        chdir(__DIR__);
    }

    public function pwdProvider()
    {
        $expects = [
            __DIR__ . DIRECTORY_SEPARATOR . '..',
            __DIR__ . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..',
        ];
        
        $obj = new Filesystem(
            new StubFilesystem($expects),
        );
        
        return [
            [
                $obj,
                $expects[0],
            ],
            [
                $obj,
                $expects[1],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider pwdProvider
    */
    public function pwd(
        Filesystem $obj,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $result = chdir($expect);
        
        if ($result === false) {
            throw new RuntimeException(
                "preparation failure:pwd()",
            );
        }
        
        $this->assertEquals(
            $expect,
            $obj->pwd(),
        );
    }

    public function chdirProvider()
    {
        $expects = [
            __DIR__ . DIRECTORY_SEPARATOR . '..',
            __DIR__ . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..',
        ];
        
        $obj = new Filesystem(
            new StubFilesystem($expects),
        );
        
        return [
            [
                $obj,
                $expects[0],
            ],
            [
                $obj,
                $expects[1],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider chdirProvider
    */
    public function chdir(
        Filesystem $obj,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $obj->chdir($expect);
        
        $this->assertEquals(
            $expect,
            $obj->pwd(),
        );
    }

    public function existsProvider()
    {
        $expects = [
            true,
            false,
        ];
        
        $obj = new Filesystem(
            new StubFilesystem($expects),
        );
        
        return [
            [
                $obj,
                __DIR__ . DIRECTORY_SEPARATOR . '..',
                $expects[0],
            ],
            [
                $obj,
                __DIR__ . DIRECTORY_SEPARATOR . 'DUMMY',
                $expects[1],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider existsProvider
    */
    public function exists(
        Filesystem $obj,
        string $file,
        bool $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $this->assertEquals(
            $expect,
            $obj->exists($file),
        );
    }
}
