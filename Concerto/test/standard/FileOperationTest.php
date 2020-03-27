<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\FileOperation;
use Concerto\mbstring\MbString;

class FileOperationTest extends ConcertoTestCase
{
    private $tmp;
    
    public function setUp(): void
    {
        $this->tmp = __DIR__ . DIRECTORY_SEPARATOR . 'tmp';
    }
    
    private function cleanup()
    {
        if (!file_exists($this->tmp)) {
            mkdir($this->tmp);
        }
    }
    
    /**
    * @test
    */
    public function SuccessStandard()
    {
//      $this->markTestIncomplete();
        
        $this->cleanup();
        
        //copy
        $obj = new FileOperation(false);
        
        $src    = $this->tmp . DIRECTORY_SEPARATOR . 'aaa.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . 'bbb.txt';
        file_put_contents($src, '漢字を表示する');
        
        $obj->copy($src, $dest);
        $this->assertFileEquals($src, $dest);
        
        
        $src    = $this->tmp . DIRECTORY_SEPARATOR . '漢字.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . '漢字1.txt';
        
        //php7.1
        // $src1    = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'auto'));
        // $dest1   = MbString::escape5c(mb_convert_encoding($dest, 'SJIS', 'auto'));
        $src1   = $src;
        $dest1  = $dest;
        
        file_put_contents($src1, '漢字を表示する');
        
        $obj->copy($src, $dest);
        $this->assertFileEquals($src1, $dest1);
        
        //5c文字対応
        $src    = $this->tmp . DIRECTORY_SEPARATOR . '表示.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . '表示1.txt';
        
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
        $src    = $this->tmp . DIRECTORY_SEPARATOR . 'aaa.txt';
        $obj->delete($src);
        $this->assertFileNotExists($src);
        
        $src    = $this->tmp . DIRECTORY_SEPARATOR . '漢字.txt';
        $src1   = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'UTF8'));
        $obj->delete($src);
        $this->assertFileNotExists($src);
        
        $src    = $this->tmp . DIRECTORY_SEPARATOR . '表示.txt';
        $src1   = MbString::escape5c(mb_convert_encoding($src, 'SJIS', 'UTF8'));
        $obj->delete($src);
        $this->assertFileNotExists($src);
        
        
        
        //rename
        $src    = $this->tmp . DIRECTORY_SEPARATOR . 'bbb.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . 'ccc.txt';
        $obj->rename($src, $dest);
        $this->assertFileExists($dest);
        
        $src    = $this->tmp . DIRECTORY_SEPARATOR . '漢字1.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . '漢字2.txt';
        
        //php7.1
        // $dest1   = MbString::escape5c(mb_convert_encoding($dest, 'SJIS', 'UTF8'));
        $dest1  = $dest;
        
        $obj->rename($src, $dest);
        $this->assertFileExists($dest1);
        
        $src    = $this->tmp . DIRECTORY_SEPARATOR . '表示1.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . '表示2.txt';
        
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
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $obj = new FileOperation();
        $src    = $this->tmp . DIRECTORY_SEPARATOR . 'zzz.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . 'yyy.txt';
        $obj->copy($src, $dest);
    }
    
    /**
    * @test
    */
    public function ExceptionDelete()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $obj = new FileOperation();
        $src    = $this->tmp . DIRECTORY_SEPARATOR . 'zzz.txt';
        $obj->delete($src);
    }
    
    /**
    * @test
    */
    public function ExceptionRename()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $obj = new FileOperation();
        $src    = $this->tmp . DIRECTORY_SEPARATOR . 'zzz.txt';
        $dest   = $this->tmp . DIRECTORY_SEPARATOR . 'yyy.txt';
        $obj->rename($src, $dest);
    }
    
    
    /**
    * @test
    */
    public function createTempDir()
    {
        $this->markTestIncomplete();
        
        
        
        /*
        $target = $src . '\\test1.txt';
        file_put_contents($target, 'aaa');
        touch($target, strtotime('-1 day'));
        $obj->createTempDir($src, 2);
        $this->assertFileExists($src);
        $obj->createTempDir($src, 1);
        $this->assertFileNotExists($src);
        */
    }
}
