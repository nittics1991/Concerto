<?php

declare(strict_types=1);

namespace test\Concerto\conf;

use test\Concerto\ConcertoTestCase;
use Concerto\conf\ConfigReaderArray;
use Concerto\standard\ArrayUtil;

class ConfigReaderArrayTest extends ConcertoTestCase
{
    /**
    * @test
    */
    public function ExceptionConstruct()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'read.zzz';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file not found');
        $object = new ConfigReaderArray($file);
    }

    /**
    * @test
    */
    public function SuccessFileRead()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileName = 'read.php';
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $fileName;
        $obj = new ConfigReaderArray($file);
        $actual = $obj->read();

        $expect = [
            'database' => [
                'default' => [
                    'adapter' => 'pgsql',
                    'params' => [
                        'host' => 'localhost',
                        'port' => '5432',
                        'dbname' => 'postgres',
                        'user' => 'postgres',
                        'password' => 'manager'
                    ]
                ]
            ]
            , 'log' => [
                'default' => [
                    'stream' => 'err.log',
                    'format' => '%s:%s' . PHP_EOL
                ]
            ]
        ];
        $this->assertEquals($expect, $actual);
    }
}
