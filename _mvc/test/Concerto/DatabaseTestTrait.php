<?php

/**
*   DatabaseTestTrait
*
*   @version 220128
*/

declare(strict_types=1);

namespace test\Concerto;

use InvalidArgumentException;
use PDO;
use PDOStatement;

trait DatabaseTestTrait
{
    /**
    *   @var ?PDO
    */
    public ?PDO $pdo;

    /**
    *   executeQuery
    *
    *   @param string $tableName
    *   @param ?array $binds [bindName => bindValue]
    *   @param ?PDO $pdo
    *   @return int
    */
    public function executeQuery(
        string $sql,
        ?array $binds,
        ?PDO $pdo,
    ): PDOStatement {
        $binds = $binds ?? [];
        $bindTypes = $bindTypes ?? [];
        $pdo = $pdo ?? $this->pdo;

        $stmt = $pdo->prepare($sql);

        foreach ($binds as $bindName => $bindValue) {
            $stmt->bindValue(
                $bindName,
                $bindValue,
                is_int($bindValue) ?
                    PDO::PARAM_INT :
                    PDO::PARAM_STR,
            );
        }

        $stmt->execute();
        return $stmt;
    }

    /**
    *   createTable
    *
    *   @param string $tableName
    *   @param array $definition [カラム名 => データ型,...]
    *   @param ?PDO $pdo
    *   @return PDO
    */
    public function createTable(
        string $tableName,
        array $definition,
        ?PDO $pdo = null,
    ): PDO {
        $pdo = $pdo ?? $this->pdo;

        $sql = "CREATE TABLE {$tableName} (";

        foreach ($definition as $columnName => $dataType) {
            $sql .= "{$columnName} {$dataType},";
        }

        $sql = mb_substr($sql, 0, mb_strlen($sql) - 1) . ")";

        $this->executeQuery($sql, [], $pdo);
        return $pdo;
    }

    /**
    *   importData
    *       return imported data
    *
    *   @param string $tableName
    *   @param array $dataset [[カラム名 => データ,...],...]
    *   @param ?PDO $pdo
    *   @return PDOStatement インポート後全データ
    */
    public function importData(
        string $tableName,
        array $dataset,
        ?PDO $pdo = null,
    ): PDOStatement {
        $pdo = $pdo ?? $this->pdo;

        if (empty($dataset)) {
            throw new InvalidArgumentException(
                "empty $dataset"
            );
        }

        $columnNames = array_keys(current($dataset));

        $sql = "INSERT INTO {$tableName} (" .
            implode(
                ',',
                $columnNames,
            ) .
            ") VALUES ";

        $bindValues = [];
        $cnt = 0;

        foreach ($dataset as $data) {
            $bindNames = [];


            foreach ($data as $val) {
                $bindName = ":{$cnt}";
                $bindNames[] = $bindName;
                $bindValues[$bindName] = $val;
                $cnt++;
            }

            $sql .= "(" .
                implode(
                    ',',
                    $bindNames,
                ) .
                "),";
        }

        $sql = mb_substr($sql, 0, mb_strlen($sql) - 1);
        $this->executeQuery($sql, $bindValues, $pdo);

        $sql = "SELECT * FROM {$tableName}";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    /**
    *   rowCount
    *
    *   @param string $tableName
    *   @param ?PDO $pdo
    *   @return int
    */
    public function rowCount(
        string $tableName,
        ?PDO $pdo = null,
    ): int {
        $pdo = $pdo ?? $this->pdo;

        $sql = "SELECT COUNT(*) FROM {$tableName}";
        $stmt = $this->executeQuery($sql, [], $pdo);
        $result = $stmt->fetchColumn();
        return !is_int($result) ? 0 : $result;
    }

    /**
    *   truncateTable
    *
    *   @param string $tableName
    *   @param ?PDO $pdo
    *   @return PDO
    */
    public function truncateTable(
        string $tableName,
        ?PDO $pdo = null,
    ): PDO {
        $pdo = $pdo ?? $this->pdo;

        $sql = "DELETE FROM {$tableName}";
        $stmt = $this->executeQuery($sql, [], $pdo);
        return $pdo;
    }
}
