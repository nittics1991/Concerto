<?php

declare(strict_types=1);

namespace Concerto\test\conf\conf;

use Concerto\test\ConcertoTestCase;
use BadMethodCallException;
use Concerto\conf\conf\Config;
use Concerto\standard\ArrayUtil;

class ConfigTest extends ConcertoTestCase
{
    /**
    *
    */
    public function initData()
    {
        return [
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
        ];
    }

    /**
    *   @test
    */
    public function doItMethod()
    {
//      $this->markTestIncomplete();

        $config = new Config($this->initData());
        $name = 'log.default.stream';

        //
        $this->assertEquals(true, $config->has($name));
        $this->assertEquals(true, isset($config[$name]));

        $this->assertEquals('err.log', $config->get($name));
        $this->assertEquals('err.log', $config[$name]);

        //
        $initData = $this->initData();
        $allData = $config->toArray();
        $this->assertEquals([], ArrayUtil::compare($initData, $allData));

        //
        $newConfig = $config->set($name, 'new value');
        $this->assertEquals('new value', $newConfig->get($name));

        //
        $changData['database']['default']['params']['port'] = 1111;
        $changData['log']['default']['new'] = 'AAA';
        $expect = $initData;
        $expect['database']['default']['params']['port'] = 1111;
        $expect['log']['default']['new'] = 'AAA';

        $target = new Config($changData);
        $replaced = $config->replace($target);
        $this->assertEquals($expect, $replaced->toArray());
    }

    /**
    *   @test
    *   @expectedException BadMethodCallException
    *   @expectedExceptionMessage unsupported method:offsetSet
    */
    public function setException()
    {
        $this->markTestIncomplete();

        $config = new Config($this->initData());
        $name = 'log.default.stream';
        $config[$name];

        try {
            $config[$name];
        } catch (BadMethodCallException $e) {
            $this->assertEquals(1, 1);
        } catch (Exception $e) {
            $this->assertEquals(1, 0);
        }
    }

    /**
    *   @test
    *   @expectedException BadMethodCallException
    *   @expectedExceptionMessage unsupported method:offsetUnset
    */
    public function unsetException()
    {
        $this->markTestIncomplete();

        $config = new Config($this->initData());
        $name = 'log.default.stream';
        unset($config[$name]);

        try {
            unset($config[$name]);
        } catch (BadMethodCallException $e) {
            $this->assertEquals(1, 1);
        } catch (Exception $e) {
            $this->assertEquals(1, 0);
        }
    }
}
