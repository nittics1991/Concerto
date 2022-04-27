<?php

/**
*   PDOArrayTable
*
*   @version 211202
*/

declare(strict_types=1);

namespace Concerto\sql\arrayTable;

use BadMethodCallException;
use DateTime;
use DateTimeInterface;
use Exception;
use PDO;
use RuntimeException;

class PDOArrayTable
{
    /**
    *   @var string
    */
    public const  TYPE_INTEGER = 'integer';
    public const  TYPE_NUMERIC = 'numeric';
    public const  TYPE_TEXT = 'text';
    public const  TYPE_TIMESTAMP = 'timestamp';

    /**
    *   @var PDO
    */
    protected PDO $pdo;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
    *   {inherit}
    *
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {
        if (method_exists($this->pdo, $name)) {
            return call_user_func_array(
                [$this->pdo, $name],
                $arguments
            );
        }

        throw new BadMethodCallException(
            "not defined method:{$name}"
        );
    }

    /**
    *   createTableFromArrayTable
    *
    *   @param string $table_name
    *   @param array $data
    *   @param ?array $schema
    *   @return array schema
    */
    public function createTableFromArrayTable(
        string $table_name,
        array $data,
        ?array $schema = null,
    ): array {
        $schema = $schema ??
            $this->createTableSchema($data);

        if (empty($schema)) {
            return [];
        }

        $create_sql = $this->buildCreateTableSql(
            $table_name,
            $schema
        );

        list($insert_sql, $binds) =
            $this->buildInsertStatement(
                $table_name,
                $schema,
                $data
            );

        $this->doCreateTableFromArrayTable(
            $schema,
            $create_sql,
            $insert_sql,
            $binds
        );

        return $schema;
    }

    /**
    *   createTableSchema
    *
    *   @param array $data
    *   @return array [column_name => data_type,...]
    */
    protected function createTableSchema(
        array $data
    ): array {
        $schema = [];

        foreach ($data as $list) {
            if (!is_array($list)) {
                continue;
            }

            foreach ($list as $key => $val) {
                $data_type = gettype($val);

                if (array_key_exists($key, $schema)) {
                    continue;
                } elseif ($data_type === 'integer') {
                    $schema[$key] = PDOArrayTable::TYPE_INTEGER;
                } elseif ($data_type === 'double') {
                    $schema[$key] = PDOArrayTable::TYPE_NUMERIC;
                } elseif ($data_type === 'boolean') {
                    $schema[$key] = PDOArrayTable::TYPE_TEXT;
                } elseif (
                    $val instanceof DateTimeInterface ||
                    (
                        $data_type === 'string' &&
                        strtotime((string)$val) !== false
                    )
                ) {
                    $schema[$key] = PDOArrayTable::TYPE_TIMESTAMP;
                } elseif ($data_type === 'string') {
                    $schema[$key] = PDOArrayTable::TYPE_TEXT;
                }
            }
        }

        return $schema;
    }

    /**
    *   buildCreateTableSql
    *
    *   @param string $table_name
    *   @param array $schema
    *   @return string
    */
    protected function buildCreateTableSql(
        string $table_name,
        array $schema
    ): string {
        $columns = [];

        foreach ($schema as $column => $data_type) {
            $columns[] = "{$column} {$data_type}";
        }

        return "CREATE TABLE {$table_name} ( " .
            implode(',', $columns) .
            " )";
    }

    /**
    *   buildInsertStatement
    *
    *   @param string $table_name
    *   @param array $schema
    *   @param array $data
    *   @return array [sql, binds]
    */
    protected function buildInsertStatement(
        string $table_name,
        array $schema,
        array $data
    ): array {
        //カラム名の順序を定義
        $define_column_index = array_flip(array_keys($schema));

        $values = [];
        $binds = [];
        $row_index = 0;

        foreach ($data as $list) {
            if (!is_array($list)) {
                continue;
            }

            $values_inner = [];

            foreach ($define_column_index as $key => $column_index) {
                $values_inner[$key] = ":{$row_index}_{$column_index}";
                $binds[":{$row_index}_{$column_index}"] = null;
            }

            foreach ($list as $key => $val) {
                $column_index = $define_column_index[$key] ?? null;

                if ($column_index === null) {
                    continue;
                } elseif (
                    $schema[$key] === PDOArrayTable::TYPE_TIMESTAMP &&
                    $val instanceof DateTimeInterface
                ) {
                    $binds[":{$row_index}_{$column_index}"] =
                        $val->format(DateTimeInterface::ATOM);
                } elseif (
                    $schema[$key] === PDOArrayTable::TYPE_TIMESTAMP &&
                    strtotime($val) !== false
                ) {
                    $binds[":{$row_index}_{$column_index}"] =
                        (new DateTime())
                        ->setTimestamp(strtotime($val))
                        ->format(DateTimeInterface::ATOM);
                } elseif ($schema[$key] === PDOArrayTable::TYPE_INTEGER) {
                    $binds[":{$row_index}_{$column_index}"] =
                        (int)$val;
                } elseif ($schema[$key] === PDOArrayTable::TYPE_NUMERIC) {
                    $binds[":{$row_index}_{$column_index}"] =
                        (float)$val;
                } elseif (
                    $schema[$key] === PDOArrayTable::TYPE_TEXT &&
                    is_bool($val)
                ) {
                    $binds[":{$row_index}_{$column_index}"] =
                        $val === true ? '1' : '0';
                } elseif ($schema[$key] === PDOArrayTable::TYPE_TEXT) {
                    $binds[":{$row_index}_{$column_index}"] =
                        (string)$val;
                }
            }

            if (!empty($values_inner)) {
                $values[] = implode(',', $values_inner);
                $row_index++;
            }
        }

        $sql = "INSERT INTO {$table_name} ( " .
            implode(',', array_keys($schema)) .
            " ) VALUES ( " .
            implode(' ),( ', $values) .
            " )";

        return [$sql, $binds];
    }

    /**
    *   doCreateTableFromArrayTable
    *
    *   @param array $schema
    *   @param string $create_sql
    *   @param string $insert_sql,
    *   @param array $binds
    */
    protected function doCreateTableFromArrayTable(
        array $schema,
        string $create_sql,
        string $insert_sql,
        array $binds
    ): void {
        $error_mode = $this->pdo->getAttribute(
            PDO::ATTR_ERRMODE
        );

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->pdo->beginTransaction();

            $this->doCreateTable($create_sql);
            $this->doInsertData(
                $schema,
                $insert_sql,
                $binds,
            );

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        } finally {
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                $error_mode
            );
        }
    }

    /**
    *   doCreateTable
    *
    *   @param string $create_sql
    */
    protected function doCreateTable(
        string $create_sql,
    ): void {
            $stmt = $this->pdo->prepare($create_sql);
            $stmt->execute();
    }

    /**
    *   doInsertData
    *
    *   @param array $schema
    *   @param string $insert_sql
    *   @param array $binds
    */
    protected function doInsertData(
        array $schema,
        string $insert_sql,
        array $binds
    ) {
        if (empty($binds)) {
            return;
        }

        $stmt = $this->pdo->prepare($insert_sql);

        $param_data_types = array_values($schema);

        foreach ($binds as $param => $val) {
            $exploded = explode('_', $param);
            $bind_parameter = PDO::PARAM_STR;

            if (!isset($exploded[1])) {
                throw new RuntimeException(
                    "pdo bind error:{$param}"
                );
            }

            if ($exploded[1] === 'integer') {
                $bind_parameter = PDO::PARAM_INT;
            }

            $stmt->bindValue($param, $val, $bind_parameter);
        }

        $stmt->execute();
    }

    /**
    *   情報スキーマcolumns
    *
    *   @param ?string $table_name
    *   @return array
    */
    public function schemaColumns(
        ?string $table_name = null
    ): array {
        $sql = "
            SELECT *
            FROM information_schema.columns
            WHERE (
                table_name = :table_name
                ) IS NOT FALSE
            ORDER BY ordinal_position
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':table_name', $table_name, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
