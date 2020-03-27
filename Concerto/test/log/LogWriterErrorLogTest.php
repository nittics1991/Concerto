<?php

declare(strict_types=1);

namespace Concerto\test\log;

use Concerto\test\ConcertoTestCase;
use Concerto\log\LogWriterErrorLog;

class LogWriterErrorLogTest extends ConcertoTestCase
{
    private $class;
    
    protected function setUp(): void
    {
        $config = array(
            'log' => array(
                'default' => array(
                    'stream' => __DIR__ . '\log\\err.log',
                    'format' => '%s'
                )
            )   //END log
        );
        
        $this->class = new LogWriterErrorLog($config);
    }
    
    public function testSuccessDataSet()
    {
//      $this->markTestIncomplete();
        
        $this->class->write('エラーメッセージ' . PHP_EOL);
        $this->class->setFormat('%d,ERROR_NO=%d,%s');
        $this->class->write(array(date('Ymd His'), 9999, 'エラーメッセージ' . PHP_EOL));
        
        $this->assertEquals(1, 1);
    }
    
    /**
    */
    public function testMessageException()
    {
//      $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('write error');
        $this->class->write(array(date('Ymd His'), 9999, 'エラーメッセージ' . PHP_EOL));
    }
}
