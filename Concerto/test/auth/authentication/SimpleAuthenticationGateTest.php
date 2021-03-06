<?php

declare(strict_types=1);

namespace Concerto\test\auth\authentication;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authentication\SimpleAuthenticationGate;

class SimpleAuthenticationGateTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicSuccess()
    {
        // $this->markTestIncomplete();

        $id = 'user1';
        $password = 'password1';

        $obj = new SimpleAuthenticationGate($id, $password);

        $this->assertEquals(true, $obj->login($id, $password));
        $this->assertEquals(false, $obj->login($id, 'DUMMY'));
        $this->assertEquals(false, $obj->login('DUMMY', $password));
    }
}
