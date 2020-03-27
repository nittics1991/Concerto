<?php

namespace Concerto\test\auth;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\AuthLdapDB;
use Concerto\auth\AuthDbBaseFactory;
use Concerto\auth\AuthConst;

class AuthDbLdapTest extends ConcertoTestCase
{
    
    protected function setUp(): void
    {
    }
    
    /**
    *   @test
    **/
    public function login1()
    {
//      $this->markTestIncomplete();
        
        $factory = $this->createMock(AuthDbBaseFactory::class);
        $object = new AuthLdapDB($factory);
        
        $this->assertEquals(false, $object->login('user', 'pass'));
    }
}
