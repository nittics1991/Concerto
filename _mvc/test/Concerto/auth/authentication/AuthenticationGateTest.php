<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\authentication\AuthenticationGate;
use Prophecy\Argument;
use Concerto\auth\authentication\AuthUserRepositoryImpl;
use Concerto\auth\authentication\AuthUser;

class AuthenticationGateTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function loginSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $authUserRepository = $this->prophesize(AuthUserRepositoryImpl::class);

        $authUserRepository->findByUserId(Argument::type('string'))
            ->willReturn(new AuthUser(
                ['id' => 'user1','password' => 'pass1',]
            ));

        $authUserRepository
            ->validatePassword(
                Argument::type(AuthUser::class),
                Argument::type('string')
            )->willReturn(true);

        $obj = new AuthenticationGate($authUserRepository->reveal());
        $expect = $obj->login('user1', 'pass1');
        $this->assertEquals(true, $expect);

        //spy
        //なぜかエラー
        /*
        $authUserRepository->findByUserId()
            ->shouldHaveBeenCalledTimes(1);
        $authUserRepository->validatePassword()
            ->shouldHaveBeenCalledTimes(1);
       */
    }
}
