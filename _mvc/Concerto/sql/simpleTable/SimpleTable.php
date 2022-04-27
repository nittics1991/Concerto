<?php

/**
*   SimpleTable
*
*   @version 210916
*/

declare(strict_types=1);

namespace Concerto\sql\simpleTable;

use PDO;
use RuntimeException;
use Concerto\standard\ModelDb;

class SimpleTable
{
    /**
    *   @var string[] ['ModelDataType' => 'DBColumnType', ...]
    */
    protected $type_convert_map = [
        'boolean' => 'TEXT',
        'integer' => 'INTEGER',
        'double' => 'DOUBLE PRECISION',
        'string' => 'TEXT',
        'datetime' => 'TIMESTAMP',
    ];

    /**
    *   @var PDO
    */
    protected PDO $pdo;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(
        PDO $pdo,
    ) {
        $this->pdo = $pdo;
    }

    /**
    *   createFromModelDb
    *
    *   @param ModelDb $modelDb
    */
    public function createFromModelDb(
        ModelDb $modelDb
    ): void {
        $sql = $this->buildCreateSqlFromModelDb($modelDb);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    /**
    *   buildCreateSqlFromModelDb
    *
    *   @param ModelDb $modelDb
    *   @return string
    */
    protected function buildCreateSqlFromModelDb(
        ModelDb $modelDb
    ): string {
        $table_name = $this->getTableName($modelDb);

        $column_definitions =
            $this->getColumnDefinitions($modelDb);

        $definitions = [];

        foreach ($column_definitions as $name => $type) {
            $db_type = $this->convertDbType($type);
            $definitions[] = "'{$name}' {$db_type} ";
        }

        $sql = "CREATE TABLE {$table_name} (";
        $sql .= implode(',', $definitions);
        $sql .= ')';

        return $sql;
    }

    /**
    *   getTableName
    *
    *   @param ModelDb $modelDb
    *   @return string
    */
    protected function getTableName(
        ModelDb $modelDb
    ): string {
        return $modelDb->getSchema();
    }

    /**
    *   getColumnDefinitions
    *
    *   @param ModelDb $modelDb
    *   @return array [column_name=>type,...]
    */
    protected function getColumnDefinitions(
        ModelDb $modelDb
    ): array {
        $modelData = $modelDb->createModel();
        return $modelData->getInfo();
    }

    /**
    *   convertDbType
    *
    *   @param string $model_data_type
    *   @return string
    */
    protected function convertDbType(
        string $model_data_type
    ): string {
        if (
            !array_key_exists(
                $model_data_type,
                $this->type_convert_map
            )
        ) {
            throw new RuntimeException(
                "undefined convert type:{$model_data_type}"
            );
        }
        return $this->type_convert_map[$model_data_type];
    }

    /**
    *   setTypeConvertMap
    *
    *   @param string $model_data_type
    *   @param string $db_type
    *   @return static
    */
    public function setTypeConvertMap(
        string $model_data_type,
        string $db_type,
    ): static {
        $this->type_convert_map[$model_data_type] = $db_type;
        return $this;
    }

    /**
    *   getPDO
    *
    *   @return PDO
    */
    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
