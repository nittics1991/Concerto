<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\HttpDownload;
use org\bovigo\vfs\vfsStream;
use RuntimeException;

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
    */
    #[Test]
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
    *   ダウンロード
    *
    */
    #[Test]
    public function send()
    {
    //*   @runInSeparateProcessがあると動かない(phpunit bug)
        // $this->markTestIncomplete('--- markTestIncomplete ---');

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


        //phpunit11 try-catchの処理がダメそう
        // try {
            // $class->send($file);
        // } catch (RuntimeException $e) {
            // $this->assertEquals(1, 1);
            // return;
        // }

        // $this->assertEquals(1, 0);
    }

    /**
    *   ダウンロード失敗
    *
    */
    #[Test]
    public function sendException()
    {
    //*   @runInSeparateProcessがあると動かない(phpunit bug)
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(RuntimeException::class);
        $class = new HttpDownload();
        $class->send("notFile");
    }
}
