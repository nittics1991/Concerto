<?php

declare(strict_types=1);

namespace Concerto\test\conf;

use Concerto\test\ConcertoTestCase;
use Concerto\conf\ConfigReaderJson;
use Concerto\standard\ArrayUtil;

class ConfigReaderJsonTest extends ConcertoTestCase
{
    /**
    * @test
    */
    public function ExceptionConstruct()
    {
//      $this->markTestIncomplete();
        
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'read.zzz';
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('file not found');
        $object = new ConfigReaderJson($file);
    }
    
    /**
    * @test
    */
    public function SuccessFileRead()
    {
//      $this->markTestIncomplete();
        
        $fileName = 'read.json';
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $fileName;
        $obj = new ConfigReaderJson($file);
        $actual = $obj->read();
        
        $expect = array(
            'database' => array (
                'default' => array(
                    'adapter' => 'pgsql',
                    'params' => array(
                        'host' => 'localhost',
                        'port' => '5432',
                        'dbname' => 'postgres',
                        'user' => 'postgres',
                        'password' => 'manager'
                    )
                )
            )
            , 'log' => array(
                'default' => array(
                    'stream' => 'err.log',
                    'format' => '%s:%s'
                )
            )
        );
        $this->assertEquals($expect, $actual);
    }
}
