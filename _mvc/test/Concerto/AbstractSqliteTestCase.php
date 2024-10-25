<?php

/**
*   sqlite test case
*/

declare(strict_types=1);

namespace test\Concerto;

use PDO;
use test\Concerto\ConcertoTestCase;
use Concerto\sql\simpleTable\Sqlite;
use Concerto\standard\{
    ModelData,
    ModelDb,
};

abstract class AbstractSqliteTestCase extends ConcertoTestCase
{
    use DatabaseTestTrait;

    protected $dns = 'sqlite::memory:';

    /**
    *   PDO初期化
    *
    */
    public function initPdo()
    {
        if (!isset($this->pdo)) {
            $this->pdo = new PDO(
                $this->dns,
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ],
            );
        }
        return $this->pdo;
    }

    /**
    *   テーブル作成
    *
    *   @param ModelDb $table テーブル定義
    *   @param ModelData $columns カラム定義
    *   @return string テーブル名
    */
    public function setupTable(ModelDb $table, ModelData $columns)
    {
        if ($this->existsTable($table)) {
            return;
        }

        $sqlite = new Sqlite($table, $columns);
        $sql = $sqlite->createTable();
        $this->initPdo();
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $splits = explode(' ', $sql);
        return (isset($splits[2])) ? $splits[2] : null;
    }

    /**
    *   テーブル存在確認
    *
    *   @param ModelDb $table
    *   @return bool
    */
    public function existsTable(ModelDb $table)
    {
        $schema = $table->getSchema();
        $sql = "SELECT * 
            FROM sqlite_master
            WHERE type = 'table'
                AND name = :name
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $schema, PDO::PARAM_STR);
        $stmt->execute();
        $result = (array)$stmt->fetchAll();
        return (count($result) > 0) ? true : false;
    }

    /**
    *   全データ取得
    *
    *   @param ModelDb $table
    *   @return array
    */
    public function fetchAllData(ModelDb $table)
    {
        $sql = "SELECT * from " .
            $table->getSchema();

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   SQL実行
    *
    *   @param string $sql
    *   @return array
    */
    public function executeSql(string $sql)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
