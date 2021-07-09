<?php

/**
*
*/

declare(strict_types=1);

namespace Concerto\test;

include('e:\\program\\phar\\dbunit.phar');
use PHPUnit\DbUnit\TestCase;

use PDO;
use Concerto\test\{
    PrivateTestTrait,
    TestPdoConnectionTrait
};

abstract class abstractDatabaseTestCase extends TestCase
{
    use PrivateTestTrait;
    use TestPdoConnectionTrait;

    protected static $pdo = null;
    protected $con = null;

    final public function getConnection()
    {
        //development environment confirmation
        if (!preg_match('/543[0,4,6]/', $GLOBALS['DB_DSN'])) {
            die('development environment confirmation error');
        }

        if (
            !isset($this->con)
            || !isset(self::$pdo)
        ) {
            self::$pdo = $this->getTestConnection();

            $this->con = $this->createDefaultDBConnection(
                self::$pdo,
                $GLOBALS['DB_DBNAME']
            );
        }
        return $this->con;
    }
}
