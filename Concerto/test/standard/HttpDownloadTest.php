<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\HttpDownload;
use org\bovigo\vfs\vfsStream;

class HttpDownloadTest extends ConcertoTestCase
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
    *   @test
    **/
    public function setParam()
    {
//      $this->markTestIncomplete();
        
        $class = new HttpDownload();
        
        $params = array(
            'Cache-Control' => 'public',
            'Pragma' => 'public'
        );
        
        $class->setParam($params);
        $this->assertEquals($params, $class->getParam());
        
        $class = new HttpDownload($params);
        $this->assertEquals($params, $class->getParam());
    }
    
    /**
    *   ダウンロード
    *
    *   @test
    *   @runInSeparateProcess
    */
    public function send()
    {
        $this->markTestIncomplete();
        
        ob_start();
        
        $fileName = 'test.txt';
        $vfsStreamFile = vfsStream::newFile($fileName);
        
        $data = 'abc漢字x漢字xx漢字z';
        $vfsStreamFile->write($data);
        $this->vfsRoot->addChild($vfsStreamFile);
        
        $file = $this->vfsRootPath . DIRECTORY_SEPARATOR . $fileName;
        
        //debug
        $this->assertFileExists($file);
        $this->assertEquals($data, file_get_contents($file));
        
        $class = new HttpDownload();
        $class->send($file);
        
        $contents = ob_get_contents();
        
        ob_clean();
        
        $this->assertEquals($data, $contents);
    }
    
    /**
    *   ダウンロード失敗
    *
    *   @test
    *   @runInSeparateProcess
    */
    public function sendException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('file not found:notFile');
        $class = new HttpDownload();
        $class->send("notFile");
    }
}
