<?php

/**
*   AuthUserRepositoryImpl
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserRepositoryInterface;

class AuthUserRepositoryImpl implements AuthUserRepositoryInterface
{
    /**
    *   @var string
    */
    protected string $userIdPropertyName = 'id';

    /**
    *   @var mixed
    */
    protected mixed $factory;

    /**
    *   __construct
    *
    *   @param mixed $factory
    */
    public function __construct(
        mixed $factory
    ) {
        $this->factory = $factory;
    }

    /**
    *   @inheritDoc
    */
    public function findByUserId(
        string $userId
    ): ?AuthUserInterface {
        $userIdPropertyName = $this->userIdPropertyName;

        $dataModel = $this->factory->getDataModel();

        $dataModel->$userIdPropertyName = $userId;

        $dataMapper = $this->factory->getDataMapper();

        $result = $dataMapper->select($dataModel);

        if (count($result) !== 1) {
            return null;
        }

        return $this->factory->createAuthUser($result[0]);
    }

    /**
    *   @inheritDoc
    */
    public function exists(string $user): bool
    {
        $data = $this->findByUserId($user);

        return isset($data);
    }

    /**
    *   @inheritDoc
    */
    public function validatePassword(
        AuthUserInterface $user,
        string $password
    ): bool {
        $hasher = $this->factory->getHasher();

        return $hasher->verify(
            $password,
            (string)$user->getPassword()
        );
    }
}
