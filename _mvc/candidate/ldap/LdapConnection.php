<?php

/**
*   LdapConnection
*
*   @version 220125
*/

declare(strict_types=1);

namespace candidate\ldap;

use LDAP\Connection;
use RuntimeException;

class LdapConnection
{
    /**
    *   dns
    *
    *   @var string
    */
    protected string $dns;

    /**
    *   options
    *
    *   @var mixed[]
    */
    protected array $options;

    /**
    *   tsl
    *
    *   @var bool
    */
    protected bool $tsl = false;

    /**
    *   connection
    *
    *   @var ?Connection
    */
    protected ?Connection $connection = null;

    /**
    *   __construct
    *
    *   @param string $dns
    *   @param mixed[] $options
    */
    public function __construct(
        string $dns,
        array $options = []
    ) {
        $this->dns = $dns;
        $this->options = $options;
    }

    /**
    *   __destruct
    *
    */
    public function __destruct()
    {
        $this->unbind();
    }

    /**
    *   setOption
    *
    *   @param int $name
    *   @param array|string|int|bool $value
    *   @return $this
    */
    public function setOption(
        int $name,
        array|string|int|bool $value
    ) {
        if (!isset($this->options[$name])) {
            $this->options[$name] = $value;
        }
        return $this;
    }

    /**
    *   setOptions
    *
    *   @param mixed[] $values
    *   @return $this
    */
    public function setOptions(array $values)
    {
        foreach ($values as $name => $value) {
            $this->setOption($name, $value);
        }
        return $this;
    }

    /**
    *   attacheOption
    *
    *   @param int $name
    *   @param array|string|int|bool $value
    *   @return $this
    */
    public function attacheOption(
        int $name,
        array|string|int|bool $value
    ) {
        if (!$this->connection) {
            throw new RuntimeException(
                "connection not bound"
            );
        }

        if (!isset($this->options[$name])) {
            $this->options[$name] = $value;
            ldap_set_option($this->connection, $name, $value);
        }
        return $this;
    }

    /**
    *   getBoundOption
    *
    *   @param int $name
    *   @return mixed
    */
    public function getBoundOption(
        int $name
    ) {
        if (!$this->connection) {
            throw new RuntimeException(
                "connection not bound"
            );
        }
        $result = null;
        ldap_get_option($this->connection, $name, $result);
        return $result;
    }

    /**
    *   setTsl
    *
    *   @return $this
    */
    public function setTsl()
    {
        $this->tsl = true;
        return $this;
    }

    /**
    *   bind
    *
    *   @param string $dn
    *   @param string $password
    *   @return $this
    */
    public function bind(
        ?string $dn = null,
        ?string $password = null
    ) {
        $this->connection = ldap_connect($this->dns);
        if ($this->connection === false) {
            throw new RuntimeException(
                "connect error:{$this->dns}"
            );
        }

        foreach ($this->options as $name => $value) {
            ldap_set_option($this->connection, $name, $value);
        }

        if ($this->tsl) {
            ldap_start_tls($this->connection);
        }

        if (!isset($dn) || !isset($password)) {
            ldap_bind($this->connection);
            return $this;
        }
        ldap_bind($this->connection, $dn, $password);
        return $this;
    }

    /**
    *   unbind
    *
    *   @return void
    */
    public function unbind()
    {
        if (isset($this->connection)) {
            @ldap_unbind($this->connection);
        }
        $this->connection = null;
    }

    /**
    *   getConnection
    *
    *   @return mixed
    */
    public function getConnection()
    {
        return $this->connection;
    }
}
