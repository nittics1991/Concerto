<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\authentication\AuthSession;
// use Concerto\auth\authentication\AuthUserInterface;
use Concerto\auth\authentication\AuthUser;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class AuthSessionTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function basicSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new AuthSession('test');

        //内部でnewしているSessionCacheのstab
        $cache = $this->prophesize(
            'Psr\SimpleCache\CacheInterface'
        );

        $cache->get(
            Argument::any(),
            Argument::any(),
        )->willReturn(
            false,
            true,
        );

        $cache->set(
            Argument::any(),
            Argument::any(),
            Argument::any(),
        )->willReturn(true);

        $cache->delete(
            Argument::any(),
        )->willReturn(true);

        $this->setPrivateProperty(
            $obj,
            'session',
            $cache->reveal(),
        );

        //empty
        $this->assertEquals(false, $obj->logined());
        $this->assertEquals(null, $obj->get());

        //save
        /*
        //error対策
        //Exception: Serialization of 'Closure' is not allowed
        $authUser = $this->prophesize()
            ->willImplement('Concerto\auth\authentication\AuthUserInterface');
        $obj->save($authUser->reveal());
       */

        $authUser = new AuthUser([
            'id' => 12345,
            'password' => 'password',
        ]);

        $obj->save($authUser);
        //delete
        $obj->delete();

        //spie
        $cache->delete(Argument::any())
            ->shouldHaveBeenCalledTimes(1);
    }
}
