<?php

/**
*   AuthHistoryRepositoryImpl
*
*   @version 210610
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use DateTimeImmutable;
use Exception;
use Concerto\auth\authentication\{
    AuthHistoryRepositoryFactory,
    AuthHistoryRepositoryInterface,
    AuthUserInterface
};

class AuthHistoryRepositoryImpl implements
    AuthHistoryRepositoryInterface
{
    /**
    *   factory
    *
    *   @var AuthHistoryRepositoryFactory
    */
    protected $factory;

    /**
    *   __construct
    *
    *   @param AuthHistoryRepositoryFactory $factory
    */
    public function __construct(AuthHistoryRepositoryFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
    *   {inherit}
    *
    */
    public function record(
        AuthUserInterface $authUser,
        array $contents = []
    ): void {
        $dataModel = $this->factory->getDataModel();
        $dataModel->id = $authUser->getId();
        $dataModel->login_at = (new DateTimeImmutable())
            ->format('Y-m-d H:i:s');
        $pdo = $this->factory->getPdo();

        $dataMapper = $this->factory->getDataMapper();
        try {
            $pdo->beginTransaction();
            $dataMapper->insert([$dataModel]);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        $pdo->commit();
    }
}
