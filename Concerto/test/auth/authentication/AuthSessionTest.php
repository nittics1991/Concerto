<?php

declare(strict_types=1);

namespace Concerto\test\auth\authentication;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authentication\AuthSession;
// use Concerto\auth\authentication\AuthUserInterface;
use Concerto\auth\authentication\AuthUser;
use Psr\SimpleCache\CacheInterface;
use Prophecy\Argument;

class AuthSessionTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function basicSuccess()
    {
        // $this->markTestIncomplete();

        $obj = new AuthSession('test');

        //内部でnewしているSessionCacheのstab
        $cache = $this->prophesize()
            ->willImplement('Psr\SimpleCache\CacheInterface');
        $this->setPrivateProperty($obj, 'session', $cache->reveal());

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
        $cache->set(Argument::any(), Argument::any())
            ->shouldHaveBeenCalledTimes(1);
        $cache->delete(Argument::any())
            ->shouldHaveBeenCalledTimes(1);
    }
}
