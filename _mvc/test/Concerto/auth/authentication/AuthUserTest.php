<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\authentication\AuthUser;

class AuthUserTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function basicSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

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
    */
    #[Test]
    public function tooManyParameterException()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);

        $actual = [
            'id' => 'user1',
            'password' => 'password1',
            'dummy' => 'DUMMY'  //too many
        ];

        $obj = new AuthUser($actual);
    }
}
