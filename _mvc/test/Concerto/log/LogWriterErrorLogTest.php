<?php

declare(strict_types=1);

namespace test\Concerto\log;

use test\Concerto\ConcertoTestCase;
use Concerto\log\LogWriterErrorLog;

class LogWriterErrorLogTest extends ConcertoTestCase
{
    private $class;

    protected function setUp(): void
    {
        $config = [
            'log' => [
                'default' => [
                    'stream' => __DIR__ . '\log\\err.log',
                    'format' => '%s'
                ]
            ]   //END log
        ];

        $this->class = new LogWriterErrorLog($config);
    }

    public function testSuccessDataSet()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->class->write('エラーメッセージ' . PHP_EOL);
        $this->class->setFormat('%d,ERROR_NO=%d,%s');
        $this->class->write([date('Ymd His'), 9999, 'エラーメッセージ' . PHP_EOL]);

        $this->assertEquals(1, 1);
    }

    /**
    */
    public function testMessageException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('write error');
        $this->class->write([date('Ymd His'), 9999, 'エラーメッセージ' . PHP_EOL]);
    }
}
