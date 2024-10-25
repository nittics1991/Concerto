<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use Concerto\standard\HttpDownload;
use RuntimeException;
use VirtualFileSystem\FileSystem; 

class HttpDownloadTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function setParam()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $class = new HttpDownload();

        $params = [
            'Cache-Control' => 'public',
            'Pragma' => 'public'
        ];

        $class->setParam($params);
        $this->assertEquals($params, $class->getParam());

        $class = new HttpDownload($params);
        $this->assertEquals($params, $class->getParam());
    }

    /**
    *   @test
    */
    public function send()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileName = '/test.txt';

        $data = 'abc漢字x漢字xx漢字z';

        $fileSystem = new FileSystem();
        
        $fileObj = $fileSystem->createFile(
            $fileName,
            $data,
        );

        $url = $fileObj->url();

        $this->assertFileExists($url);

        $this->assertEquals(
            $data,
            file_get_contents($url)
        );

        $class = new HttpDownload();

        try {
            $class->send($url);
        } catch (RuntimeException $e) {
            $this->assertEquals(1, 1);
            return;
        }

        $this->assertEquals(1, 0);
    }

    /**
    *   ダウンロード失敗
    *
    *   @test
    */
    public function sendException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(RuntimeException::class);
        $class = new HttpDownload();
        $class->send("notFile");
    }
}
