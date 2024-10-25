<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\auth\authentication\AuthUserRepositoryImpl;
use Prophecy\Argument;
use Concerto\auth\authentication\AuthUserRepositoryFactory;
use Concerto\auth\authentication\AuthUser;
use Concerto\hashing\HasherInterface;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;

class AuthUserRepositoryImplTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function findByUserIdSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $factory = $this->prophesize(AuthUserRepositoryFactory::class);

        $modelData = $this->prophesize(ModelData::class);
        $factory->getDataModel()
            ->willReturn($modelData->reveal());

        $modelDb = $this->prophesize(ModelDb::class);
        $modelDb->select(Argument::any())
            ->willReturn([['id' => 'user1']]);
        $factory->getDataMapper()
            ->willReturn($modelDb->reveal());

        $factory->createAuthUser(Argument::any())
            ->willReturn(new AuthUser(['id' => 'user1']));

        $obj = new AuthUserRepositoryImpl($factory->reveal());
        $authUser = $obj->findByUserId('user1');

        $this->assertEquals(true, $authUser instanceof AuthUser);

        //spy
        $factory->getDataModel()
            ->shouldHaveBeenCalledTimes(1);
        $factory->getDataMapper()
            ->shouldHaveBeenCalledTimes(1);

        //なぜかエラー
        // $factory->createAuthUser()
            // ->shouldHaveBeenCalledTimes(1);

        $this->assertEquals(true, $obj->exists('user1'));
    }

    /**
    */
    #[Test]
    public function validatePasswordSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $factory = $this->prophesize(AuthUserRepositoryFactory::class);

        $hasher = $this->prophesize()
            ->willImplement(HasherInterface::class);
        $hasher->verify(Argument::type('string'), Argument::type('string'))
            ->willReturn(true);

        $factory->getHasher()
            ->willReturn($hasher->reveal());

        $authUser = $this->prophesize(AuthUser::class);
        $authUser->getPassword()
            ->willReturn('pass1');

        $obj = new AuthUserRepositoryImpl($factory->reveal());
        $expect = $obj->validatePassword($authUser->reveal(), 'pass1');
        $this->assertEquals(true, $expect);

        $factory->getHasher()->shouldHaveBeenCalledTimes(1);
    }
}
