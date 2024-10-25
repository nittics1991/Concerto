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
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\HttpUpload;
use RuntimeException;
use finfo;
use org\bovigo\vfs\vfsStream;

class HttpUploadTest extends ConcertoTestCase
{
    private $vfsRoot;
    private $vfsRootPath;

    protected function setUp(): void
    {
        $this->vfsRoot = vfsStream::setup();
        $this->vfsRootPath = vfsStream::url($this->vfsRoot->getName());
    }

    /**
    *   パラメータ設定
    *
    */
    #[Test]
    public function setParam()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $class = new HttpUpload();

        $params = [
            'mime' => 'text/plain'
        ];

        $expect = [
            'mime' => 'text/plain',
            'max_size' => 1000000,
            'diversion' => false
        ];

        $class->setParam($params);
        $this->assertEquals($expect, $class->getParam());

        $class = new HttpUpload($params);
        $this->assertEquals($expect, $class->getParam());
    }

    private function createFile()
    {
        $fileName = 'test.txt';
        $vfsStreamFile = vfsStream::newFile($fileName);

        $data = 'abc漢字x漢字xx漢字z';
        $vfsStreamFile->write($data);
        $this->vfsRoot->addChild($vfsStreamFile);

        $file = $this->vfsRootPath . DIRECTORY_SEPARATOR . $fileName;
        return $file;
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
    */
    #[Test]
    public function whatMime()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = $this->createFile();
        $class = new HttpUpload();
        $this->assertEquals('text/plain', $class->whatMime($file));
    }

    /**
    *   アップロード
    *
    */
    #[Test]
    public function load()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = $this->createFile();
        $this->setGrobalVariable($file);

        $this->setMime($file);

        $params = [
            'mime' => ['txt' => 'text/plain']
        ];

        $class = new HttpUpload($params);
        $path = $this->vfsRootPath;
        $result = $class->load('upfile', $path);

        $this->assertFileExists($result[0]);
        $this->assertFileEquals($file, $result[0]);
    }

    /**
    *   アップロード失敗パラメータ
    *
    */
    #[Test]
    public function loadExceptionParameter()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('parameter is invalid');
        $file = $this->createFile();
        $this->setGrobalVariable($file);

        $params = ['max_size' => 1];

        $class = new HttpUpload($params);
        $class->load('upfile');
    }

    /**
    *   アップロード失敗MIME
    *
    */
    #[Test]
    public function loadExceptionMime()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('mime is invalid');
        $file = $this->createFile();
        $this->setGrobalVariable($file);

        $class = new HttpUpload();
        $class->load('upfile');
    }

    /**
    *   アップロード失敗UPLOAD
    *
    */
    #[Test]
    public function loadExceptionMoveFile()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('ext is not match');
        $file = $this->createFile();
        $this->setGrobalVariable($file);

        $this->setMime($file);

        $params = [
            'mime' => ['text/plain']
        ];

        $class = new HttpUpload($params);
        $class->load('upfile');
    }

    /**
    *   isNull
    *
    *
    */
    #[Test]
    public function isNulls()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = $this->createFile();
        $_FILES['upfile']['name']   = $file;
        $_FILES['upfile']['size']   = filesize($file);
        $_FILES['upfile']['tmp_name']   = $file;
        $_FILES['upfile']['error']  = UPLOAD_ERR_OK;

        $class = new HttpUpload();
        $this->assertEquals(false, $class->isNull('upfile'));

        //210512　なぜか？
        // $_FILES['upfile']['name'][0]    = $file;
        $_FILES['upfile']['name'] = $file;

        $this->assertEquals(false, $class->isNull('upfile'));

        unset($_FILES);
        $_FILES['upfile']['name'] = '';
        $this->assertEquals(true, $class->isNull('upfile'));

        unset($_FILES);
        $_FILES['upfile']['name'][0] = '';
        $this->assertEquals(true, $class->isNull('upfile'));
    }
}
