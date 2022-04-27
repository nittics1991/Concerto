<?php

/**
*   AuthUserRepositoryImpl
*
*   @version 190523
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserRepositoryInterface;

class AuthUserRepositoryImpl implements AuthUserRepositoryInterface
{
    /**
    *   DataModelのユーザIDプロパティ名
    *
    *   @var string
    */
    protected $userIdPropertyName = 'id';

    /**
    *   factory
    *
    *   @var mixed
    */
    protected $factory;

    /**
    *   __construct
    *
    *   @param mixed $factory
    */
    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    /**
    *   {inherit}
    *
    */
    public function findByUserId(string $userId): ?AuthUserInterface
    {
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
    *   {inherit}
    *
    */
    public function exists(string $user): bool
    {
        $data = $this->findByUserId($user);
        return isset($data);
    }

    /**
    *   {inherit}
    *
    */
    public function validatePassword(
        AuthUserInterface $user,
        string $password
    ): bool {
        $hasher = $this->factory->getHasher();
        return $hasher->verify($password, (string)$user->getPassword());
    }
}
