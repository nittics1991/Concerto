<?php

declare(strict_types=1);

namespace test\Concerto\cache;

use test\Concerto\ConcertoTestCase;
use Concerto\cache\StandardFileCache;
use Psr\Log\LoggerInterface;

class StandardFileCacheTest extends ConcertoTestCase
{
    protected string $log_path;

    public function setUp(): void
    {
        $this->log_path = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            'StandardFileCache.log';
    }

    public function constructProvider()
    {
        return [
            [
                null,
                null,
                sys_get_temp_dir() .
                    DIRECTORY_SEPARATOR .
                    'StandardFileCache',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function construct(
        ?string $dir,
        ?LoggerInterface $logger,
        string $expect_dir
    ) {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $obj = new StandardFileCache(
            $dir,
            $logger,
        );

        $this->assertEquals(
            true,
            file_exists($expect_dir),
        );

        $this->assertEquals(
            $expect_dir,
            $this->getPrivateProperty($obj, 'dir'),
        );
    }

    /**
    *   @test
    */
    public function log()
    {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $log_path = $this->log_path;

        @unlink($log_path);

        $obj = new StandardFileCache();

        $obj->get('AAA');

        $this->assertEquals(
            true,
            file_exists($log_path)
        );

        $this->assertEquals(
            true,
            filesize($log_path) > 0
        );

        $content = file_get_contents($log_path);

        $keyword = [
            '.*class=',
            '.*method=',
            '.*key=',
            '.*result=',
        ];

        foreach ($keyword as $key) {
            $this->assertEquals(
                true,
                mb_ereg_match($key, $content),
                "error:{$key}",
            );
        }
    }

    /**
    *   @test
    */
    public function basic()
    {
//      $this->markTestIncomplete("--- markTestIncomplete ---");

        $log_path = $this->log_path;
        @unlink($log_path);

        $obj = new StandardFileCache();

        //空
        $this->assertEquals(
            false,
            $obj->has('abc'),
        );

        //初期値
        $this->assertEquals(
            'get1',
            $obj->get('abc', 'get1'),
        );

        $obj->set('def', 'set1');

        //設定値あり
        $this->assertEquals(
            true,
            $obj->has('def'),
        );

        $this->assertEquals(
            'set1',
            $obj->get('def', 'get1'),
        );

        $obj->set('ghi', 'set2');

        //設定値追加
        $this->assertEquals(
            'set2',
            $obj->get('ghi', 'get1'),
        );

        $this->assertEquals(
            'set1',
            $obj->get('def', 'get1'),
        );

        $obj->set('jkl', 'set3');
        $obj->delete('ghi');

        //削除
        $this->assertEquals(
            false,
            $obj->has('ghi'),
        );

        $this->assertEquals(
            true,
            $obj->has('def'),
        );

        $this->assertEquals(
            'set3',
            $obj->get('jkl', 'get1'),
        );

        //クリア
        $obj->clear();

        $this->assertEquals(
            false,
            $obj->has('def'),
        );

        $this->assertEquals(
            false,
            $obj->has('jkl'),
        );

        $file = new \SplFileObject($log_path);
        $cnt = 0;

        foreach ($file as $line) {
            $cnt++;
        }

        $this->assertEquals(
            17,
            $cnt,
        );
    }
}
