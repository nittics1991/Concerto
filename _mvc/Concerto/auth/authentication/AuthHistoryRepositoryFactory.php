<?php

/**
*   AuthHistoryRepositoryFactory
*
*   @version 221201
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use PDO;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
    ModelData,
    ModelDb
};

class AuthHistoryRepositoryFactory
{
    /**
    *   @var PDO
    */
    protected PDO $pdo;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(
        PDO $pdo
    ) {
        $this->pdo = $pdo;
    }

    /**
    *   getPdo
    *
    *   @return PDO
    */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
    *   getDataModel
    *
    *   @return DataModelInterface
    */
    public function getDataModel(): DataModelInterface
    {
        return new ModelData();
    }

    /**
    *   getDataMapper
    *
    *   @return ModelDb
    */
    public function getDataMapper(): DataMapperInterface
    {
        return new ModelDb($this->pdo);
    }
}
