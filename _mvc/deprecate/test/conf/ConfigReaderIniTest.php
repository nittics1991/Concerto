<?php

declare(strict_types=1);

namespace test\Concerto\conf\conf;

use test\Concerto\ConcertoTestCase;
use Concerto\conf\conf\{
    ConfigInterface,
    ConfigReaderIni
};
use Concerto\standard\ArrayUtil;

class ConfigReaderIniTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function section1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $reader = new ConfigReaderIni();

        $this->assertEquals(
            false,
            $this->getPrivateProperty($reader, 'section')
        );

        $reader->useSection();
        $this->assertEquals(
            true,
            $this->getPrivateProperty($reader, 'section')
        );
    }

    /**
    *   @test
    */
    public function mode1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $reader = new ConfigReaderIni();

        $this->assertEquals(
            ConfigReaderIni::TYPED,
            $this->getPrivateProperty($reader, 'mode')
        );

        $reader->mode(ConfigReaderIni::RAW);
        $this->assertEquals(
            ConfigReaderIni::RAW,
            $this->getPrivateProperty($reader, 'mode')
        );
    }

    /**
    *
    */
    public function buildProvider()
    {
        return [
            [
                realpath(__DIR__ . '/../data/read.ini'),
                ConfigReaderIni::NORMAL,
                false,
                [
                    'default.adapter' => 'pgsql',
                    'default.params.host' => 'localhost',
                    'default.params.port' => '5432',
                    'default.params.dbname' => 'postgres',
                    'default.params.user' => 'postgres',
                    'default.params.password' => 'manager',
                    'default' => [
                        'stream' => 'err.log',
                        'format' => '%s:%s',
                    ],
                ],
            ],  //DATA

            [
                realpath(__DIR__ . '/../data/read.ini'),
                ConfigReaderIni::TYPED,
                false,
                [
                    'default.adapter' => 'pgsql',
                    'default.params.host' => 'localhost',
                    'default.params.port' => 5432,  //TYPED動作
                    'default.params.dbname' => 'postgres',
                    'default.params.user' => 'postgres',
                    'default.params.password' => 'manager',
                    'default' => [
                        'stream' => 'err.log',
                        'format' => '%s:%s',
                    ],
                ],
            ],  //DATA

            [
                realpath(__DIR__ . '/../data/read.ini'),
                ConfigReaderIni::RAW,
                false,
                [
                    'default.adapter' => "'pgsql'", //RAW動作
                    'default.params.host' => 'localhost',
                    'default.params.port' => '5432',
                    'default.params.dbname' => 'postgres',
                    'default.params.user' => 'postgres',
                    'default.params.password' => 'manager',
                    'default' => [
                        'stream' => "'err.log'",    //RAW動作
                        'format' => "'%s:%s'",  //RAW動作
                    ],
                ],
            ],  //DATA

            [
                realpath(__DIR__ . '/../data/read.ini'),
                ConfigReaderIni::NORMAL,
                true,   //use section
                [
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
                ],
            ],  //DATA
        ];
    }

    /**
    *   @test
    *   @dataProvider buildProvider
    */
    public function build1($path, $mode, $section, $expect)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $reader = new ConfigReaderIni($mode, $section);
        $config = $reader->build($path);

        $this->assertEquals(true, $config instanceof ConfigInterface);
        $actual = $config->toArray();
        $this->assertEquals([], ArrayUtil::compare($expect, $actual));
    }
}
