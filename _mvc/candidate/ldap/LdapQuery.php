<?php

/**
*   LdapQuery
*
*   @version 190509
*/

declare(strict_types=1);

namespace candidate\ldap;

use RuntimeException;
use candidate\ldap\LdapConnection;
use candidate\ldap\LdapStmt;

class LdapQuery
{
    /**
    *   connection
    *
    *   @var LdapConnection
    */
    protected $connection;

    /**
    *   __construct
    *
    *   @param LdapConnection $connection
    */
    public function __construct(
        LdapConnection $connection
    ) {
        $this->connection = $connection;
    }

    /**
    *   search
    *
    *   @param string $baseDn
    *   @param string $filter
    *   @param mixed[] $attributes
    *   @param int $typeOnly
    *   @param int $sizeLimit
    *   @param int $timeLimit
    *   @param int $alias
    *   @return LdapStmt
    */
    public function search(
        string $baseDn,
        string $filter,
        array $attributes = [],
        int $typeOnly = 0,
        int $sizeLimit = 0,
        int $timeLimit = 0,
        int $alias = LDAP_DEREF_NEVER
    ): LdapStmt {
        return call_user_func(
            [$this, 'execute'],
            'ldap_search',
            func_get_args()
        );
    }

    /**
    *   list
    *
    *   @see search()
    */
    public function list(
        string $baseDn,
        string $filter,
        array $attributes = [],
        int $typeOnly = 0,
        int $sizeLimit = 0,
        int $timeLimit = 0,
        int $alias = LDAP_DEREF_NEVER
    ): LdapStmt {
        return call_user_func(
            [$this, 'execute'],
            'ldap_list',
            func_get_args()
        );
    }

    /**
    *   read
    *
    *   @see search()
    */
    public function read(
        string $baseDn,
        string $filter,
        array $attributes = [],
        int $typeOnly = 0,
        int $sizeLimit = 0,
        int $timeLimit = 0,
        int $alias = LDAP_DEREF_NEVER
    ): LdapStmt {
        return call_user_func(
            [$this, 'execute'],
            'ldap_read',
            func_get_args()
        );
    }

    /**
    *   execute
    *
    *   @param callable $function
    *   @param mixed[] $args
    *   @return LdapStmt
    */
    private function execute(
        callable $function,
        array $args
    ): LdapStmt {
        $dn = $this->connection->getConnection();
        array_unshift($args, $dn);

        $result = call_user_func_array($function, $args);

        return new LdapStmt($this->connection, $result);
    }
}
