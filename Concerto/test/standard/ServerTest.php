<?php

declare(strict_types=1);

namespace Concerto\test\standard;

use Concerto\test\ConcertoTestCase;
use Concerto\standard\Server;

class ServerTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function get1()
    {
//      $this->markTestIncomplete();
        
        //property lowercase only
        $array = Server::get();
        foreach ($array as $key => $val) {
            $this->assertEquals(true, mb_ereg_match('\A[_a-z0-9()]+\z', $key));
        }
        
        $this->assertEquals($array[$key], Server::get($key));
        
        //change lowercase
        $this->assertEquals($array[$key], Server::get(strtoupper($key)));
    }
    
    /**
    *   @test
    **/
    public function has1()
    {
//      $this->markTestIncomplete();
        
        $this->assertEquals(false, Server::has('dummy'));
        
        $array = Server::get();
        $key = key($array);
        $this->assertEquals(true, Server::has($key));
    }
    
    /**
    *   @test
    **/
    public function isAjax()
    {
//      $this->markTestIncomplete();
        
        $this->assertEquals(false, Server::isAjax());
        
        $_SERVER['HTTP_X_REQUESTED_WITH'] = true;
        $this->assertEquals(false, Server::isAjax());
        
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPREQUEST';
        $this->assertEquals(true, Server::isAjax());
    }
    
    /**
    *   getRequestUrl
    *
    *   @test
    **/
    public function getRequestUrl()
    {
//      $this->markTestIncomplete();
        
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
    *   @test
    **/
    public function getRequestSelfUrl()
    {
//      $this->markTestIncomplete();
        
        $_SERVER['SERVER_NAME']  = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['PHP_SELF'] = '/test/tes1.php';
        
        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}{$_SERVER['PHP_SELF']}";
        $this->assertEquals($except, Server::getRequestSelfUrl());
    }
    
    /**
    *   getRequestParentUrl
    *
    *   @test
    **/
    public function getRequestParentUrl()
    {
//        $this->markTestIncomplete();
        
        $_SERVER['SERVER_NAME'] = 'itc.co.jp';
        $_SERVER['SERVER_PORT'] = '8080';
        $_SERVER['PHP_SELF'] = '/test/tes1.php';
        
        $except = "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/test/";
        $this->assertEquals($except, Server::getRequestParentUrl());
    }
}
