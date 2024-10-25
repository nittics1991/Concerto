<?php

/**
*   AuthUser
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\auth\authentication;

use Concerto\accessor\impl\ArrayExchangerTrait;
use Concerto\auth\authentication\AuthUserInterface;

class AuthUser implements AuthUserInterface
{
    use ArrayExchangerTrait;

    /**
    *   @inheritDoc
    *   @var string[]
    */
    protected array $propertyDefinitions = [
        'id', 'password',
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $dataset
    */
    public function __construct(
        array $dataset = []
    ) {
        $this->fromArray($dataset);
    }

    /**
    *   @inheritDoc
    */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
    *   @inheritDoc
    */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
