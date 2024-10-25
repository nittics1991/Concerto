<?php

declare(strict_types=1);

namespace test\Concerto\filesystem;

use PHPUnit\Framework\TestCase;
use test\Concerto\{
    PrivateTestTrait,
};
use Concerto\filesystem\FilePath;
use test\Concerto\filesystem\StubFilesystem;

class FilePathTest extends TestCase
{
    use PrivateTestTrait;
    
    protected $separater = DIRECTORY_SEPARATOR;
    protected $unix_paths = [];
    protected $win_paths = [];
    
    protected function setUpData(): array
    {
        return DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                '/foo/bar/baz.txt',
                '/foo/bar/baz.1.txt',
                '/foo/bar/baz',
                '/foo/bar/baz/',
                'foo/bar/baz.txt',
                '/foo/./bar/baz.txt',
                '/foo/../bar/baz.txt',
            ]:
            //win
            [
                'c:\\foo\\bar\\baz.txt',
                'c:\\foo\\bar\\baz.1.txt',
                'c:\\foo\\bar\\baz',
                'c:\\foo\\bar\\baz\\',
                'foo\\bar\\baz.txt',
                '\\foo\\.\\bar\\baz.txt',
                '\\foo\\..\\bar\\baz.txt',
                '\\\\foo\\bar\\baz.txt',
            ];
    }

    public function toArrayProvider()
    {
        $paths = $this->setUpData();
        
        return //DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                [
                    $paths[0],
                    ['', 'foo', 'bar', 'baz.txt'],
                ],
                [
                    $paths[1],
                    ['', 'foo', 'bar', 'baz.1.txt'],
                ],
                [
                    $paths[2],
                    ['', 'foo', 'bar', 'baz'],
                ],
                [
                    $paths[3],
                    ['', 'foo', 'bar', 'baz', ''],
                ],
                [
                    $paths[4],
                    ['foo', 'bar', 'baz.txt'],
                ],
                [
                    $paths[5],
                    ['', 'foo', '.', 'bar', 'baz.txt'],
                ],
                [
                    $paths[6],
                    ['', 'foo', '..', 'bar', 'baz.txt'],
                ],
            ];
            //win
    }

    /**
    *   @test
    *   @dataProvider toArrayProvider
    */
    public function toArray(
        string $file,
        array $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $this->assertEquals(
            $expect,
            FilePath::toArray($file),
        );
    }

    public function createFilePiecesProvider()
    {
        $paths = $this->setUpData();
        
        return //DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                [
                    $paths[0],
                    ['baz','txt'],
                ],
                [
                    $paths[1],
                    ['baz','1','txt'],
                ],
                [
                    $paths[2],
                    ['baz'],
                ],
                [
                    $paths[3],
                    [''],
                ],
                [
                    $paths[4],
                    ['baz','txt'],
                ],
                [
                    $paths[5],
                    ['baz','txt'],
                ],
                [
                    $paths[6],
                    ['baz','txt'],
                ],
            ];
            //win
    }

    /**
    *   @test
    *   @dataProvider createFilePiecesProvider
    */
    public function createFilePieces(
        string $file,
        array $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $obj = new FilePath();
        
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod(
                $obj,
                'createFilePieces',
                [$file],
            ),
        );
    }

    public function fromArrayProvider()
    {
        $paths = $this->setUpData();
        
        return //DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                [
                    ['', 'foo', 'bar', 'baz.txt'],
                    $paths[0],
                ],
                [
                    ['', 'foo', 'bar', 'baz.1.txt'],
                    $paths[1],
                ],
                [
                    ['', 'foo', 'bar', 'baz'],
                    $paths[2],
                ],
                [
                    ['', 'foo', 'bar', 'baz', ''],
                    $paths[3],
                ],
                [
                    ['foo', 'bar', 'baz.txt'],
                    $paths[4],
                ],
                [
                    ['', 'foo', '.', 'bar', 'baz.txt'],
                    $paths[5],
                ],
                [
                    ['', 'foo', '..', 'bar', 'baz.txt'],
                    $paths[6],
                ],
            ];
            //win
    }

    /**
    *   @test
    *   @dataProvider fromArrayProvider
    */
    public function fromArray(
        array $pieces,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $this->assertEquals(
            $expect,
            FilePath::fromArray($pieces),
        );
    }

    public function fileNameProvider()
    {
        $paths = $this->setUpData();
        
        return //DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                [
                    $paths[0],
                    'baz.txt'
                ],
                [
                    $paths[1],
                    'baz.1.txt',
                ],
                [
                    $paths[2],
                    'baz',
                ],
                [
                    $paths[3],
                    '',
                ],
                [
                    $paths[4],
                    'baz.txt'
                ],
                [
                    $paths[5],
                    'baz.txt'
                ],
                [
                    $paths[6],
                    'baz.txt'
                ],
            ];
            //win
    }    

    /**
    *   @test
    *   @dataProvider fileNameProvider
    */
    public function fileName(
        string $file,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $this->assertEquals(
            $expect,
            FilePath::fileName($file),
        );
    }

    public function baseNameProvider()
    {
        $paths = $this->setUpData();
        
        return //DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                [
                    $paths[0],
                    'baz'
                ],
                [
                    $paths[1],
                    'baz.1',
                ],
                [
                    $paths[2],
                    'baz',
                ],
                [
                    $paths[3],
                    '',
                ],
                [
                    $paths[4],
                    'baz'
                ],
                [
                    $paths[5],
                    'baz'
                ],
                [
                    $paths[6],
                    'baz'
                ],
            ];
            //win
    }    

    /**
    *   @test
    *   @dataProvider baseNameProvider
    */
    public function baseName(
        string $file,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $this->assertEquals(
            $expect,
            FilePath::baseName($file),
        );
    }

    public function extensionNameProvider()
    {
        $paths = $this->setUpData();
        
        return //DIRECTORY_SEPARATOR === '/'?
            //unix
            [
                [
                    $paths[0],
                    'txt'
                ],
                [
                    $paths[1],
                    'txt',
                ],
                [
                    $paths[2],
                    '',
                ],
                [
                    $paths[3],
                    '',
                ],
                [
                    $paths[4],
                    'txt'
                ],
                [
                    $paths[5],
                    'txt'
                ],
                [
                    $paths[6],
                    'txt'
                ],
            ];
            //win
    }    

    /**
    *   @test
    *   @dataProvider extensionNameProvider
    */
    public function extensionName(
        string $file,
        string $expect,
    ) {
        //$this->markTestIncomplete('---markTestIncomplete---');
        
        $this->assertEquals(
            $expect,
            FilePath::extensionName($file),
        );
    }

    
}
