<?php

declare(strict_types=1);

namespace Concerto\test\auth\authentication;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authentication\AuthUser;

class AuthUserTest extends ConcertoTestCase
{
    /**
    *   @test
    **/
    public function basicSuccess()
    {
        // $this->markTestIncomplete();
        
        $actual = [
            'id' => 'user1',
            'password' => 'password1',
        ];
        
        $obj = new AuthUser($actual);
        $this->assertEquals($actual['id'], $obj->getId());
        $this->assertEquals($actual['password'], $obj->getPassword());
        
        $obj = new AuthUser([]);
        $this->assertEquals(null, $obj->getId());
        $this->assertEquals(null, $obj->getPassword());
    }
    
    /**
    *   @test
    **/
    public function tooManyParameterException()
    {
        // $this->markTestIncomplete();
        
        $this->expectException(\InvalidArgumentException::class);
        
        $actual = [
            'id' => 'user1',
            'password' => 'password1',
            'dummy' => 'DUMMY'  //too many
        ];
        
        $obj = new AuthUser($actual);
    }
}
