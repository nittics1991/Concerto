<?php

/**
*   AuthUserRepositoryFactory
*
*   @version 190524
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use PDO;
use Concerto\auth\authentication\AuthUser;
use Concerto\auth\authentication\AuthUserInterface;
use Concerto\hashing\HasherInterface;
use Concerto\hashing\StandardHasher;
use Concerto\standard\DataMapperInterface;
use Concerto\standard\DataModelInterface;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;

class AuthUserRepositoryFactory
{
    /**
    *   pdo
    *
    *   @var PDO
    */
    protected $pdo;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
    *   createAuthUser
    *
    *   @param mixed[] $dataset
    *   @return AuthUserInterface
    */
    public function createAuthUser(array $dataset): AuthUserInterface
    {
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
