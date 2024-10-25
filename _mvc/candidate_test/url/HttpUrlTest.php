<?php

declare(strict_types=1);

namespace test\Concerto\url;

use test\Concerto\ConcertoTestCase;
use candidate\url\HttpUrl;

class HttpUrlTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicOperation()
    {
//      $this->markTestIncomplete();

        $url = 'https://user1:pass1@example.com:8080/path/to/file.htm?key1=a&key2=b&key4=ccc#anchor';
        $obj = HttpUrl::fromString($url);

        $this->assertEquals('https', $obj->getScheme());
        $this->assertEquals('user1:pass1', $obj->getUserInfo());
        $this->assertEquals('example.com', $obj->getHost());
        $this->assertEquals('8080', $obj->getPort());
        $this->assertEquals('user1:pass1@example.com:8080', $obj->getAuthority());
        $this->assertEquals('/path/to/file.htm', $obj->getPath());
        $this->assertEquals('file.htm', $obj->getBasename());
        $this->assertEquals('/path/to', $obj->getDirname());
        $this->assertEquals('key1=a&key2=b&key4=ccc', $obj->getQuery());
        $this->assertEquals('b', $obj->getQueryParameter('key2', 'default'));
        $this->assertEquals('default', $obj->getQueryParameter('key0', 'default'));
        $this->assertEquals(true, $obj->hasQueryParameter('key2'));

        $expect = [
            'key1' => 'a',
            'key2' => 'b',
            'key4' => 'ccc',
        ];
        $this->assertEquals($expect, $obj->getAllQueryParameters());
        $this->assertEquals('anchor', $obj->getFragment());

        $expect = ['path', 'to', 'file.htm'];
        $this->assertEquals($expect, $obj->getSegments());
        $this->assertEquals('path', $obj->getSegment(1, 'default'));
        $this->assertEquals('file.htm', $obj->getSegment(-1, 'default'));
        $this->assertEquals('default', $obj->getSegment(10, 'default'));
        $this->assertEquals('path', $obj->getFirstSegment());
        $this->assertEquals('file.htm', $obj->getLastSegment());

        $obj = $obj->withScheme('http');
        $this->assertEquals('http', $obj->getScheme());

        $obj = $obj->withUserInfo('user2:pass2');
        $this->assertEquals('user2:pass2', $obj->getUserInfo());

        $obj = $obj->withHost('replace.co.jp');
        $this->assertEquals('replace.co.jp', $obj->getHost());

        $obj = $obj->withPort(1234);
        $this->assertEquals('1234', $obj->getPort());

        $obj = $obj->withPath('/aaa/bbb/index.htm');
        $this->assertEquals('/aaa/bbb/index.htm', $obj->getPath());

        $obj = $obj->withBasename('test.htm');
        $this->assertEquals('/aaa/bbb/test.htm', $obj->getPath());

        $obj = $obj->withDirname('/ccc/ddd/');
        $this->assertEquals('/ccc/ddd/test.htm', $obj->getPath());

        $obj = $obj->withQuery('aaa=A&bbb=B');
        $this->assertEquals('aaa=A&bbb=B', $obj->getQuery());

        $obj = $obj->withQueryParameter('ccc', '12');
        $this->assertEquals('aaa=A&bbb=B&ccc=12', $obj->getQuery());

        $obj = $obj->withoutQueryParameter('bbb');
        $this->assertEquals('aaa=A&ccc=12', $obj->getQuery());

        $obj = $obj->withFragment('link');
        $this->assertEquals('link', $obj->getFragment());

        $obj2 = clone $obj;
        $this->assertEquals($obj, $obj2);
        $this->assertEquals(true, $obj->compare($obj2));

        $expect = 'http://user2:pass2@replace.co.jp:1234/ccc/ddd/test.htm?aaa=A&ccc=12#link';
        $this->assertEquals($expect, $obj->__toString());
    }
}
