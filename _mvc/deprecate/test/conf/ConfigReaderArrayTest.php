<?php

declare(strict_types=1);

namespace test\Concerto\conf\conf;

use test\Concerto\ConcertoTestCase;
use Concerto\conf\conf\{
    ConfigInterface,
    ConfigReaderArray
};
use Concerto\standard\ArrayUtil;

class ConfigReaderArrayTest extends ConcertoTestCase
{
    /**
    *
    */
    public function buildProvider()
    {
        return [
            [
                realpath(__DIR__ . '/../data/read.php'),
                [
                    'database' => [
                        'default' => [
                            'adapter' => 'pgsql',
                            'params' => [
                                'host' => 'localhost',
                                'port' => '5432',
                                'dbname' => 'postgres',
                                'user' => 'postgres',
                                'password' => 'manager'
                            ],
                        ],
                    ],   //END database
                    'log' => [
                        'default' => [
                            'stream' => 'err.log',
                            'format' => '%s:%s' . PHP_EOL
                        ],
                    ],   //END log
                ],
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider buildProvider
    */
    public function build1($path, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $reader = new ConfigReaderArray();
        $config = $reader->build($path);

        $this->assertEquals(true, $config instanceof ConfigInterface);

        $actual = $config->toArray();
        $this->assertEquals([], ArrayUtil::compare($expect, $actual));
    }
}
