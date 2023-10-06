<?php

/**
*
*/

declare(strict_types=1);

namespace test\Concerto;

@include('e:\\program\\phar\\dbunit.phar');
@include(__DIR__ . '/../../../../bin/dbunit.phar');
use PHPUnit\DbUnit\TestCase;

use RuntimeException;
use PDO;
use test\Concerto\{
    PrivateTestTrait,
};

abstract class AbstractDatabaseTestCase extends TestCase
{
    use PrivateTestTrait;

    protected static $pdo = null;
    protected $con = null;

    final public function getConnection()
    {
        //development environment confirmation
        if (
            (
                extension_loaded("pdo-pgsql") ||
                extension_loaded("pgsql")
             ) &&
            !preg_match('/543[0,4,6]/', $GLOBALS['DB_DSN'])
        ) {
            throw new RuntimeException(
                "PostgreSQL DNS ERROR"
            );
        }

        if (
            !isset($this->con) ||
            !isset(self::$pdo)
        ) {
            self::$pdo = new PDO(
                $GLOBALS['DB_DSN'],
                $GLOBALS['DB_USER'] ?? null,
                $GLOBALS['DB_PASSWD'] ?? null
            );

            $this->con = $this->createDefaultDBConnection(
                self::$pdo,
                $GLOBALS['DB_DBNAME'] ?? null
            );
        }
        return $this->con;
    }
}
