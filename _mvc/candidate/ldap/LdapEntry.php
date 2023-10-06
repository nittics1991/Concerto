<?php

/**
*   LdapEntry
*
*   @version 190509
*/

declare(strict_types=1);

namespace candidate\ldap;

class LdapEntry
{
    /**
    *   dn
    *
    *   @var string
    */
    protected $dn;

    /**
    *   attributes
    *
    *   @var mixed[]
    */
    protected $attributes;

    /**
    *   __construct
    *
    *   @param string $dn
    *   @param mixed[] $attributes
    */
    public function __construct(string $dn, array $attributes = [])
    {
        $this->dn = $dn;
        $this->attributes = $attributes;
    }

    /**
    *   getDn
    *
    *   @return string
    */
    public function getDn(): string
    {
        return $this->dn;
    }

    /**
    *   getAttributes
    *
    *   @return mixed[]
    */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
    *   has
    *
    *   @param string $name
    *   @return bool
    */
    public function has(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    /**
    *   get
    *
    *   @param string $name
    *   @return mixed
    */
    public function get(string $name)
    {
        return $this->has($name) ?
            $this->attributes[$name] : null;
    }
}
