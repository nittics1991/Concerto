<?php

declare(strict_types=1);

namespace test\Concerto\conf;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\conf\ConfigReaderIni;
use Concerto\standard\ArrayUtil;

class ConfigReaderIniTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function ExceptionConstruct()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'read.zzz';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file not found');
        $object = new ConfigReaderIni($file);
    }

    /**
    */
    #[Test]
    public function SuccessFileRead()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileName = 'read.ini';
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $fileName;
        $obj = new ConfigReaderIni($file);
        $expect = $obj->recursive()
            ->read();

        $actual = [
            'database' => [
                'default.adapter' => 'pgsql',
                'default.params.host' => 'localhost',
                'default.params.port' => '5432',
                'default.params.dbname' => 'postgres',
                'default.params.user' => 'postgres',
                'default.params.password' => 'manager',
            ],
            'log' => [
                'default' => [
                    'stream' => 'err.log',
                    'format' => '%s:%s',
                ],
            ],
        ];
        $this->assertEquals($expect, $actual);
    }
}
