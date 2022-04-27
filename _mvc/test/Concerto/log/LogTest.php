<?php

declare(strict_types=1);

namespace test\Concerto\log;

use test\Concerto\ConcertoTestCase;
use Concerto\log\Log;
use Concerto\log\LogWriterErrorLog;
use Psr\Log\LogLevel;

class DummyMessage
{
    public function __toString()
    {
    }
}

//////////////////////////////////////////////////////////////////////

class LogTest extends ConcertoTestCase
{
    private $object;

    protected function setUp(): void
    {
    }

    public function testSuccessDataSet()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        //文字列を渡してログ出力
        $config1 = [
            'log' => [
                'default' => [
                    'stream' => __DIR__ . '\log\\err.log',
                    'format' => '%s'
                ]
            ]   //END log
        ];

        $writer = new LogWriterErrorLog($config1);
        $object = new Log($writer);
        $object->write('エラーメッセージ' . PHP_EOL);

        //ログライタ変更
        $config2 = [
            'log' => [
                'default' => [
                    'stream' => __DIR__ . '\log\\err2.log',
                    'format' => '%s'
                ]
            ]   //END log
        ];

        $writer2 = new LogWriterErrorLog($config2);
        $writer2->setFormat('%d,ERROR_NO=%d,%s');
        $object = new Log($writer2);
        $object->write([date('Ymd His'), 9999, 'エラーメッセージ' . PHP_EOL]);

        //ログライタ追加
        $object = new Log($writer);
        $object->addWriter($writer2);
        $object->write([
                ['エラーメッセージ2' . PHP_EOL],
                [date('Ymd His'), 9999, 'エラーメッセージ2' . PHP_EOL]
            ]);

        $this->assertEquals(1, 1);
    }

    public function depthProvider()
    {
        return [
            [
                [1, 2, [3, 4, [5, 6], 7], 8],
                3
            ],
            [
                [1, 2, [3, 4]],
                2
            ],
            [
                [1, 2],
                1
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider depthProvider
    */
    public function depth($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $object = new Log($stub);
        $this->assertEquals($expect, $this->callPrivateMethod($object, 'depth', [$data]));
    }

    public function setLimitProvider()
    {
        return [
            [Log::WARNING, Log::WARNING],
            [LogLevel::WARNING, Log::WARNING],
        ];
    }

    /**
    *   @test
    *   @dataProvider setLimitProvider
    */
    public function setLimit($data, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $object = new Log($stub);
        $this->callPrivateMethod($object, 'setLimit', [$data]);
        $this->assertEquals($expect, $this->getPrivateProperty($object, 'limit'));
    }

    /**
    *   @test
    */
    public function setLimitException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('limit not defined');
        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $object = new Log($stub);
        $this->callPrivateMethod($object, 'setLimit', ['DUMMY']);
    }

    public function interpolateProvider()
    {
        $stub = $this
          ->createMock(DummyMessage::class)
        ;

        $message = 'string message';

        $stub
          ->method('__toString')
          ->willReturn($message)
        ;

        return [
          ['hello {name} world', ['name' => 'ABC'], 'hello ABC world'],
          ['hello {year} world', ['year' => 2017], 'hello 2017 world'],
          ['import {msg}', ['msg' => $stub], "import {$message}"],
        ];
    }

    /**
    *   @test
    *   @dataProvider interpolateProvider
    */
    public function interpolate($message, $context, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $object = new Log($stub);
        $this->assertEquals(
            $expect,
            $this->callPrivateMethod($object, 'interpolate', [$message, $context])
        );
    }

    /**
    *   @test
    */
    public function logException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('level not defined');
        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $object = new Log($stub);
        $object->log('DUMMY', 'message');
    }

    public function logProvider()
    {
        $message = 'ABC';

        return [
            [0, 1, $message, [$message]],
            [4, 4, $message, [$message]],
        ];
    }

    /**
    *   @test
    *   @dataProvider logProvider
    */
    public function log1($level, $limit, $message, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $stub
            ->expects($this->once())
            ->method('write')
            ->with($this->equalTo($expect))
        ;

        $object = new Log($stub, $limit);
        $object->log($level, $message);
    }

    public function log2Provider()
    {
        $message = 'ABC';

        return [
            [2, 1, $message, null],
            [4, 2, $message, null],
        ];
    }

    /**
    *   @test
    *   @dataProvider log2Provider
    */
    public function log2_not_write_call($level, $limit, $message, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $stub
            ->expects($this->never())
            ->method('write')
            ->with($this->equalTo($expect))
        ;

        $object = new Log($stub, $limit);
        $object->log($level, $message);
    }

    public function log3Provider()
    {
        $stub = $this
          ->createMock(DummyMessage::class)
        ;

        $message = 'string message';

        $stub
          ->method('__toString')
          ->willReturn($message)
        ;

        return [
          [
              LOG::WARNING,
              LOG::ERROR,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              LOG::WARNING,
              LOG::ERROR,
              'import {msg}',
              [],
              ["import {msg}"]
          ],
        ];
    }

    /**
    *   @test
    *   @dataProvider log3Provider
    */
    public function log3_message_context($level, $limit, $message, $context, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $stub
            ->expects($this->once())
            ->method('write')
            ->with($this->equalTo($expect))
        ;

        $object = new Log($stub, $limit);
        $object->log($level, $message, $context);
    }

    public function directLogProvider()
    {
        $stub = '';
        $message = '';

        return [
          [
              'emergency',
              LOG::EMERGENCY,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'alert',
              LOG::ALERT,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'critical',
              LOG::CRITICAL,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'error',
              LOG::ERROR,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'warning',
              LOG::WARNING,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'notice',
              LOG::NOTICE,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'info',
              LOG::INFO,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
          [
              'debug',
              LOG::DEBUG,
              'import {msg}',
              ['msg' => $stub],
              ["import {$message}"]
          ],
        ];
    }

    /**
    *   @test
    *   @dataProvider directLogProvider
    */
    public function directLog($method, $limit, $message, $context, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stub = $this
            ->createMock(LogWriterErrorLog::class)
        ;

        $stub
            ->expects($this->once())
            ->method('write')
            ->with($this->equalTo($expect))
        ;

        $object = new Log($stub, $limit);
        $object->$method($message, $context);
    }
}
