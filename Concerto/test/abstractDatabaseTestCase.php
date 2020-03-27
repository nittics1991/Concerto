<?php

/**
*
*/

declare(strict_types=1);

namespace Concerto\test;

include('e:\\program\\phar\\dbunit.phar');
use PHPUnit\DbUnit\TestCase;

use PDO;
use Concerto\test\PrivateTestTrait;

abstract class abstractDatabaseTestCase extends TestCase
{
    protected static $pdo = null;
    protected $con = null;
    
    use PrivateTestTrait;
    
    final public function getConnection()
    {
        //development environment confirmation
        if (!preg_match('/5430/', $GLOBALS['DB_DSN'])) {
            die('development environment confirmation error');
        }
        
        if ($this->con === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }
            
            $this->con = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }
        return $this->con;
    }
}
