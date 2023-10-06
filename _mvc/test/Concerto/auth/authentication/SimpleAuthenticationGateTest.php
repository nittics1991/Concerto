<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use test\Concerto\ConcertoTestCase;
use Concerto\auth\authentication\SimpleAuthenticationGate;

class SimpleAuthenticationGateTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $id = 'user1';
        $password = 'password1';

        $obj = new SimpleAuthenticationGate($id, $password);

        $this->assertEquals(true, $obj->login($id, $password));
        $this->assertEquals(false, $obj->login($id, 'DUMMY'));
        $this->assertEquals(false, $obj->login('DUMMY', $password));
    }
}
