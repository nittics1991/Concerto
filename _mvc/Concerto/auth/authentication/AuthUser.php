<?php

/**
*   AuthUser
*
*   @version 190523
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\accessor\impl\ArrayExchangerTrait;
use Concerto\auth\authentication\AuthUserInterface;

class AuthUser implements AuthUserInterface
{
    use ArrayExchangerTrait;

    /**
    *   {necessary}
    *
    *   @var string[]
    */
    protected $propertyDefinitions = [
        'id', 'password',
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $dataset
    */
    public function __construct(array $dataset = [])
    {
        $this->fromArray($dataset);
    }

    /**
    *   {inherit}
    *
    */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
    *   {inherit}
    *
    */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
