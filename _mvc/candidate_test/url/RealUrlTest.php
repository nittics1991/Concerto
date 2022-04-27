<?php

declare(strict_types=1);

namespace test\Concerto\url;

use test\Concerto\ConcertoTestCase;
use candidate\url\RealUrl;

class RealUrlTest extends ConcertoTestCase
{
    public function constructCallProvider()
    {
        return [
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                'http://www.example.com',
            ],
            [
                'http://user:pass@www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                'http://user:pass@www.example.com',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider constructCallProvider
    */
    public function constructCall($baseUrl, $origin)
    {
//      $this->markTestIncomplete();

        $obj = new RealUrl($baseUrl);
        $this->assertEquals($origin, $this->getPrivateProperty($obj, 'origin'));
    }

    public function buildProvider()
    {
        return [
            //full path
            [
                'https://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                'https://phpunit.de/manual/current/ja/writing-tests-for-phpunit.html?data1=Z&data2=X#link',
                'https://phpunit.de/manual/current/ja/writing-tests-for-phpunit.html?data1=Z&data2=X#link',
            ],
            //anchor
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '#link',
                'http://www.example.com/path/to/index.htm#link',
            ],
            //query
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '?data1=Z&data2=X',
                'http://www.example.com/path/to/index.htm?data1=Z&data2=X',
            ],
            //file+query+anchor
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                'file.htm?data1=Z&data2=X#link',
                'http://www.example.com/path/to/file.htm?data1=Z&data2=X#link',
            ],
            //path+file
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '/aaa/bbb/file.htm',
                'http://www.example.com/aaa/bbb/file.htm',
            ],
            //path+file+query+anchor
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '/aaa/bbb/file.htm?data1=Z&data2=X#link',
                'http://www.example.com/aaa/bbb/file.htm?data1=Z&data2=X#link',
            ],
            //current path
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                './',
                'http://www.example.com/path/to/',
            ],
            //parent path
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '../',
                'http://www.example.com/path/',
            ],
            //current path+file+query+anchor
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                './aaa/file.htm?data1=Z&data2=X#link',
                'http://www.example.com/path/to/aaa/file.htm?data1=Z&data2=X#link',
            ],
            //parent path+file+query+anchor
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '../../aaa/file.htm?data1=Z&data2=X#link',
                'http://www.example.com/aaa/file.htm?data1=Z&data2=X#link',
            ],
            //relative path
            [
                'http://www.example.com/path/to/index.htm?key1=a&key2=b#anchor',
                '.././..//aaa/file.htm',
                'http://www.example.com/aaa/file.htm',
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider buildProvider
    */
    public function build($baseUrl, $url, $expect)
    {
//      $this->markTestIncomplete();

        $obj = new RealUrl($baseUrl);
        $this->assertEquals($expect, $obj->build($url));
    }
}
