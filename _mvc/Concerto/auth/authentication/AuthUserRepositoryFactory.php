<?php

/**
*   AuthUserRepositoryFactory
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use PDO;
use Concerto\auth\authentication\{
    AuthUser,
    AuthUserInterface
};
use Concerto\hashing\{
    HasherInterface,
    StandardHasher
};
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
    ModelData,
    ModelDb
};

class AuthUserRepositoryFactory
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
    *   createAuthUser
    *
    *   @param mixed[] $dataset
    *   @return AuthUserInterface
    */
    public function createAuthUser(
        array $dataset
    ): AuthUserInterface {
        return new AuthUser($dataset);
    }

    /**
    *   getHasher
    *
    *   @return HasherInterface
    */
    public function getHasher(): HasherInterface
    {
        return new StandardHasher();
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
    *   @return DataMapperInterface
    */
    public function getDataMapper(): DataMapperInterface
    {
        return new ModelDb($this->pdo);
    }
}
