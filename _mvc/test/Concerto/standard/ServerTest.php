<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\Server;

class ServerTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function get1()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        //property lowercase only
        $array = Server::get();

        //なぜか$_SERVERに「!ignore!」というキーがある
        foreach ($array as $key => $val) {
            $this->assertEquals(
                true,
                mb_ereg_match('\A[_!a-z0-9():]+\z', $key),
                "error:{$key}"
            );
        }

        $this->assertEquals($array[$key], Server::get($key));

        //change lowercase
        $this->assertEquals($array[$key], Server::get(strtoupper($key)));
    }

    /**
    */
    #[Test]
    public function has1()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(false, Server::has('dummy'));

        $array = Server::get();
        $key = key($array);
        $this->assertEquals(true, Server::has($key));
    }

    /**
    */
    #[Test]
    public function isAjax()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(false, Server::isAjax());

        $_SERVER['HTTP_X_REQUESTED_WITH'] = true;
        $this->assertEquals(false, Server::isAjax());

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPREQUEST';
        $this->assertEquals(true, Server::isAjax());
    }

    /**
    *   getRequestUrl
    *
    */
    #[Test]
    public function getRequestUrl()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals('', Server::getRequestUrl());

        $_SERVER['SERVER_NAME'] = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['REQUEST_URI'] = '/test/tes1.php';

        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$_SERVER['REQUEST_URI']}";
        $this->assertEquals($except, Server::getRequestUrl());
    }

    /**
    *   getRequestSelfUrl
    *
    */
    #[Test]
    public function getRequestSelfUrl()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $_SERVER['SERVER_NAME']  = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['PHP_SELF'] = '/test/tes1.php';

        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$_SERVER['PHP_SELF']}";
        $this->assertEquals($except, Server::getRequestSelfUrl());
    }

    /**
    *   getRequestParentUrl
    *
    */
    #[Test]
    public function getRequestParentUrl()
    {
//        $this->markTestIncomplete('--- markTestIncomplete ---');

        $_SERVER['SERVER_NAME'] = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['PHP_SELF'] = '/test/tes1.php';

        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/test/";
        $this->assertEquals($except, Server::getRequestParentUrl());
    }

    /**
    *   getRequestOrigin
    *
    */
    #[Test]
    public function getRequestOrigin()
    {
//        $this->markTestIncomplete('--- markTestIncomplete ---');

        $_SERVER['SERVER_NAME'] = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['PHP_SELF'] = '/test/tes1.php';

        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/";
        $this->assertEquals($except, Server::getRequestOrigin());
    }

    /**
    *   getRequestMainUrl
    *
    */
    #[Test]
    public function getRequestMainUrl()
    {
//        $this->markTestIncomplete('--- markTestIncomplete ---');

        $_SERVER['SERVER_NAME'] = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['REQUEST_URI'] = '/test/tes1.php';

        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/";
        $this->assertEquals($except, Server::getRequestMainUrl());

        $_SERVER['REQUEST_URI'] = '/itc_work5/test/tes1.php';

        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/";
        $except .= 'itc_work5/';
        $this->assertEquals($except, Server::getRequestMainUrl());
    }
}
