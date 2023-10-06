<?php

declare(strict_types=1);

namespace test\Concerto\conf;

use test\Concerto\ConcertoTestCase;
use Concerto\conf\Config;
use Concerto\conf\ConfigReaderArray;

class ConfigTest extends ConcertoTestCase
{
    /**
    *   @test
    *
    */
    public function accessor()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $fileName = 'read.php';
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $fileName;
        $obj = new Config(new ConfigReaderArray($file));

        //get
        $expect = 'pgsql';
        $actual = $obj['database']['default']['adapter'];
        $this->assertEquals($expect, $actual);

        $expect = [
            'default' => [
                'stream' => 'err.log',
                'format' => '%s:%s' . PHP_EOL
            ]
        ];
        $actual = $obj->log;
        $this->assertEquals($expect, $actual);

        //toArray
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
        $actual = $obj->getArrayCopy();
        $this->assertEquals($expect, $actual);

        //set
        $obj->database =
            ['default' =>
                ['adapter' => 'oracle']
            ];
        $expect = 'oracle';
        $actual = $obj['database']['default']['adapter'];
        $this->assertEquals($expect, $actual);
    }

    /**
    *   @test
    */
    public function replace()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $baseFile = realpath(__DIR__ . '/data/read.php');
        $obj = new Config(new ConfigReaderArray($baseFile));

        $replaceFile = realpath(__DIR__ . '/data/replace.php');
        $obj->replace(new ConfigReaderArray($replaceFile));

        $expected = [
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
                    'stream' => 'errReplace.log',   //changed
                    'format' => '%s:%s' . PHP_EOL
                ]
            ]
        ];

        $this->assertEquals($expected, $obj->getArrayCopy());
    }

    /**
    *   @test
    */
    public function dotNotation()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $file = realpath(__DIR__ . '/data/read.php');
        $obj = new Config(new ConfigReaderArray($file));

        $this->assertEquals(true, $obj->has('database.default.params.host'));
        $this->assertEquals(false, $obj->has('database.default.params.DUMMY'));
        $this->assertEquals('localhost', $obj->get('database.default.params.host'));

        $new_data = 'CHANGED';
        $obj2 = $obj->set('database.default.params.host', $new_data);
        $this->assertEquals($new_data, $obj->get('database.default.params.host'));

        $obj2->remove('database.default.params.host');
        $this->assertEquals(false, $obj->has('database.default.params.host'));
    }
}
