<?php

declare(strict_types=1);

namespace Concerto\test\auth\authentication;

use Concerto\test\ConcertoTestCase;
use Concerto\auth\authentication\AuthHistoryRepositoryImpl;
use Prophecy\Argument;
use PDO;
use Concerto\auth\authentication\AuthHistoryRepositoryFactory;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;
use Concerto\auth\authentication\AuthUser;

class AuthHistoryRepositoryImplTest extends ConcertoTestCase
{
    /**
    *   @test
    */
    public function recodeSuccess()
    {
        // $this->markTestIncomplete();

        $factory = $this->prophesize(AuthHistoryRepositoryFactory::class);

        $modelData = $this->prophesize(ModelData::class);
        $factory->getDataModel()
            ->willReturn($modelData->reveal());

        $modelDb = $this->prophesize(ModelDb::class);
        $factory->getDataMapper()
            ->willReturn($modelDb->reveal());

        $pdo = $this->prophesize(PDO::class);
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
