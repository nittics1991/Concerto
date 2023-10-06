<?php

//function overwrite
declare(strict_types=1);

namespace Concerto\standard;

function move_uploaded_file($filename, $destination)
{
    return copy($filename, $destination);
}




namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use Concerto\standard\HttpUpload;
use RuntimeException;
use finfo;
use VirtualFileSystem\FileSystem; 

class HttpUploadTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function setParam()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new HttpUpload();

        $params = [
            'mime' => 'text/plain'
        ];

        $expect = [
            'mime' => 'text/plain',
            'max_size' => 1000000,
            'diversion' => false
        ];

        $obj->setParam($params);
        $this->assertEquals($expect, $obj->getParam());

        $obj = new HttpUpload($params);
        $this->assertEquals($expect, $obj->getParam());
    }

    private function createFile()
    {
        $fileName = '/test.txt';

        $data = 'abc漢字x漢字xx漢字z';
        
        $fileSystem = new FileSystem();
        
        $fileObj = $fileSystem->createFile(
            $fileName,
            $data,
        );
        
        return $fileObj;
    }

    private function setGrobalVariable($file)
    {
        $_FILES['upfile']['name']   = $file;
        $_FILES['upfile']['size']   = filesize($file);
        $_FILES['upfile']['tmp_name']   = $file;
        $_FILES['upfile']['error']  = UPLOAD_ERR_OK;
    }

    private function setMime($file)
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $info = $finfo->file($file);

        $_FILES['upfile']['type']   = $info;
    }

    /**
    *   MEME check
    *
    *   @test
    */
    public function whatMime()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileObj = $this->createFile();
        $file = $fileObj->url();
        
        
var_dump(mime_content_type($file));echo "\n";        
        
        
        
        
        $obj = new HttpUpload();
        $this->assertEquals('text/plain', $obj->whatMime($file));
    }

    /**
    *   アップロード
    *
    *   @test
    */
    public function load()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileObj = $this->createFile();
        $file = $fileObj->url();
        
        $this->setGrobalVariable($file);

        $this->setMime($file);

        $params = [
            'mime' => ['txt' => 'text/plain']
        ];

        $obj = new HttpUpload($params);
        // $path = $this->vfsRootPath;
        $result = $obj->load('upfile', $file);

        $this->assertFileExists($result[0]);
        $this->assertFileEquals($file, $result[0]);
    }

    /**
    *   アップロード失敗パラメータ
    *
    *   @test
    */
    public function loadExceptionParameter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('parameter is invalid');
        $fileObj = $this->createFile();
        $file = $fileObj->url();
        $this->setGrobalVariable($file);

        $params = ['max_size' => 1];

        $obj = new HttpUpload($params);
        $obj->load('upfile');
    }

    /**
    *   アップロード失敗MIME
    *
    *   @test
    */
    public function loadExceptionMime()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('mime is invalid');
        $fileObj = $this->createFile();
        $file = $fileObj->url();
        $this->setGrobalVariable($file);

        $obj = new HttpUpload();
        $obj->load('upfile');
    }

    /**
    *   アップロード失敗UPLOAD
    *
    *   @test
    */
    public function loadExceptionMoveFile()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('ext is not match');
        $fileObj = $this->createFile();
        $file = $fileObj->url();
        $this->setGrobalVariable($file);

        $this->setMime($file);

        $params = [
            'mime' => ['text/plain']
        ];

        $obj = new HttpUpload($params);
        $obj->load('upfile');
    }

    /**
    *   isNull
    *
    *   @test
    *
    */
    public function isNulls()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileObj = $this->createFile();
        $file = $fileObj->url();
        $_FILES['upfile']['name']   = $file;
        $_FILES['upfile']['size']   = filesize($file);
        $_FILES['upfile']['tmp_name']   = $file;
        $_FILES['upfile']['error']  = UPLOAD_ERR_OK;

        $obj = new HttpUpload();
        $this->assertEquals(false, $obj->isNull('upfile'));

        //210512　なぜか？
        // $_FILES['upfile']['name'][0]    = $file;
        $_FILES['upfile']['name'] = $file;

        $this->assertEquals(false, $obj->isNull('upfile'));

        unset($_FILES);
        $_FILES['upfile']['name'] = '';
        $this->assertEquals(true, $obj->isNull('upfile'));

        unset($_FILES);
        $_FILES['upfile']['name'][0] = '';
        $this->assertEquals(true, $obj->isNull('upfile'));
    }
}
