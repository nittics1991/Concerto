<?php

/**
*   AuthUserRepositoryFactoryInterface
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserInterface;
use Concerto\hashing\HasherInterface;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
};

interface AuthUserRepositoryFactoryInterface
{
    /**
    *   createAuthUser
    *
    *   @param mixed[] $dataset
    *   @return AuthUserInterface
    */
    public function createAuthUser(
        array $dataset
    ): AuthUserInterface;

    /**
    *   getHasher
    *
    *   @return HasherInterface
    */
    public function getHasher(): HasherInterface;

    /**
    *   getDataModel
    *
    *   @return DataModelInterface
    */
    public function getDataModel(): DataModelInterface;

    /**
    *   getDataMapper
    *
    *   @return DataMapperInterface
    */
    public function getDataMapper(): DataMapperInterface;
}
