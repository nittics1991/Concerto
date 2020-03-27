<?php

/**
*   SimpleAuthenticationGate
*
*   @ver 190607
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\auth\authentication\AuthUserInterface;
use Concerto\auth\authentication\AuthUserRepositoryInterface;

class SimpleAuthUserRepositoryImpl implements
    AuthUserRepositoryInterface
{
    /**
    *   users
    *
    *   @var array ['id' => 'password', ...]
    **/
    private $users;
    
    /**
    *   __construct
    *
    *   @param array $users
    **/
    public function __construct(array $users)
    {
        $this->users = $users;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function findByUserId(string $user): ?AuthUserInterface
    {
        if (!array_key_exists($user, $this->users)) {
            return null;
        }
        
        return new class ($user, $this->users[$user]) implements
            AuthUserInterface
        {
            private $userId;
            private $password;
            
            public function __construct(string $userId, string $password)
            {
                $this->userId = $userId;
                $this->password = $password;
            }
            
            public function getId(): ?string
            {
                return $this->userId;
            }
            
            public function getPassword(): ?string
            {
                return $this->password;
            }
        };
    }
    
    /**
    *   {inherit}
    *
    **/
    public function exists(string $user): bool
    {
        return array_key_exists($user, $this->users);
    }
    
    /**
    *   {inherit}
    *
    **/
    public function validatePassword(
        AuthUserInterface $user,
        string $password
    ): bool {
        if (!$this->exists((string)$user->getId())) {
            return false;
        }
        return $user->getPassword() === $password;
    }
}
