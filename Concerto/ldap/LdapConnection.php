<?php

/**
*   LdapConnection
*
*   @version 191216
**/

declare(strict_types=1);

namespace Concerto\ldap;

use RuntimeException;

class LdapConnection
{
    /**
    *   dns
    *
    *   @var string
    **/
    protected $dns;
    
    /**
    *   options
    *
    *   @var array
    **/
    protected $options;
    
    /**
    *   tsl
    *
    *   @var bool
    **/
    protected $tsl = false;
    
    /**
    *   connection
    *
    *   @var mixed
    **/
    protected $connection;
    
    /**
    *   __construct
    *
    *   @param string $dns
    *   @param array $options
    **/
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
    **/
    public function __destruct()
    {
        $this->unbind();
    }
    
    /**
    *   setOption
    *
    *   @param int $name
    *   @param mixed $value
    *   @return $this
    **/
    public function setOption(int $name, $value)
    {
        if (!isset($this->options[$name])) {
            $this->options[$name] = $value;
        }
        return $this;
    }
    
    /**
    *   setOptions
    *
    *   @param array $values
    *   @return $this
    **/
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
    *   @param mixed $value
    *   @return $this
    **/
    public function attacheOption(int $name, $value)
    {
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
    **/
    public function getBoundOption(int $name)
    {
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
    **/
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
    **/
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
        
        //ldap_bind(resource, [string, [string]])だがバグで
        //仕様上NULLが使える
        //ldap_bind(resource, [?string, [?string]])
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
    **/
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
    **/
    public function getConnection()
    {
        return $this->connection;
    }
}
