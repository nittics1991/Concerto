<?php

/**
*   sqlite test case
*/

declare(strict_types=1);

namespace Concerto\test;

include('e:\\program\\phar\\dbunit.phar');
use PHPUnit\DbUnit\TestCase;

use PDO;
use Concerto\test\PrivateTestTrait;
use Concerto\sql\simpleTable\Sqlite;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;

abstract class abstractSqliteTestCase extends TestCase
{
    protected $dns = 'sqlite::memory:';
    protected static $pdo;
    protected $con;
    
    use PrivateTestTrait;
    
    /**
    *   {inherit}
    *
    **/
    final public function getConnection()
    {
        if (!isset($this->con)) {
            $this->initPdo();
            $this->con = $this->createDefaultDBConnection(
                self::$pdo,
                mb_substr($this->dns, 7)
            );
        }
        return $this->con;
    }
    
    /**
    *   PDO初期化
    *
    **/
    public function initPdo()
    {
        if (!isset(self::$pdo)) {
            self::$pdo = new PDO(
                $this->dns,
                null,
                null,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }
        return self::$pdo;
    }
    
    /**
    *   テーブル作成
    *
    *   @param ModelDb テーブル定義
    *   @param ModelData カラム定義
    *   @return string テーブル名
    **/
    public function setupTable(ModelDb $table, ModelData $columns)
    {
        if ($this->existsTable($table)) {
            return;
        }
        
        $sqlite = new Sqlite($table, $columns);
        $sql = $sqlite->createTable();
        $this->initPdo();
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute();
        
        $splits = explode(' ', $sql);
        return (isset($splits[2])) ? $splits[2] : null;
    }
    
    /**
    *   テーブル存在確認
    *
    *   @param ModelDb
    *   @return bool
    **/
    public function existsTable(ModelDb $table)
    {
        $schema = $table->getSchema();
        $sql = "SELECT * 
            FROM sqlite_master
            WHERE type = 'table'
                AND name = :name
        ";
        
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindValue(':name', $schema, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return (count($result) > 0) ? true : false;
    }
}
