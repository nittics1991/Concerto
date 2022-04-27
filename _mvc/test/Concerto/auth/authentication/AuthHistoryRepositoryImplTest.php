<?php

declare(strict_types=1);

namespace test\Concerto\auth\authentication;

use Concerto\auth\authentication\{
    AuthHistoryRepositoryFactory,
    AuthHistoryRepositoryImpl,
    AuthUser
};
use Concerto\standard\{
    ModelData,
    ModelDb,
};
use PDO;
use Prophecy\Argument;
use test\Concerto\ConcertoTestCase;

class AuthHistoryRepositoryImplTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function recodeSuccess()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $factory = $this->prophesize(
            AuthHistoryRepositoryFactory::class
        );

        $modelData = $this->prophesize(ModelData::class);

        $factory->getDataModel()
            ->willReturn($modelData->reveal());

        $modelDb = $this->prophesize(ModelDb::class);

        $factory->getDataMapper()
            ->willReturn($modelDb->reveal());

        $pdo = $this->prophesize(PDO::class);
        $pdo->beginTransaction()->willReturn(true);
        $pdo->commit()->willReturn(true);
        $pdo->rollBack()->willReturn(true);

        $factory->getPdo()
            ->willReturn($pdo->reveal());

        $authUser = $this->prophesize(AuthUser::class);
        $authUser->getId()
            ->willReturn('user1');

        $obj = new AuthHistoryRepositoryImpl($factory->reveal());
        $obj->record($authUser->reveal());

        //spy
        $factory->getDataModel()
            ->shouldHaveBeenCalledTimes(1);
        $factory->getDataMapper()
            ->shouldHaveBeenCalledTimes(1);
        $factory->getPdo()
            ->shouldHaveBeenCalledTimes(1);
        $pdo->beginTransaction()
            ->shouldHaveBeenCalledTimes(1);
        $modelDb->insert(Argument::type('array'))
            ->shouldHaveBeenCalledTimes(1);
    }
}
