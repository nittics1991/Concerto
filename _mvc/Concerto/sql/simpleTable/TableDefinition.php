<?php

/**
*   Simple Table Definition
*
*   @version 210916
*/

declare(strict_types=1);

namespace Concerto\sql\simpleTable;

use RuntimeException;
use Concerto\standard\{
    ModelData,
    ModelDb
};

/**
*   @template TValue
*/
class TableDefinition
{
    /**
    *   @var string[] ['ModelDataType' => 'DBColumnType', ...]
    */
    protected array $columnMap = [
        'boolean' => 'TEXT',
        'integer' => 'INTEGER',
        'double' => 'DOUBLE PRECISION',
        'string' => 'TEXT',
        'datetime' => 'TIMESTAMP'
    ];

    /**
    *   @var ModelDb
    */
    protected ModelDb $modelDb;

    /**
    *   @var ModelData<TValue>
    */
    protected ModelData $modelData;

    /**
    *   __construct
    *
    *   @param ModelDb $modelDb
    *   @param ModelData<TValue> $modelData
    */
    public function __construct(
        ModelDb $modelDb,
        ModelData $modelData
    ) {
        $this->modelDb = $modelDb;
        $this->modelData = $modelData;
    }

    /**
    *   CREATE TABLE
    *
    *   @return string
    */
    public function createTable(): string
    {
        $schema = $this->modelDb->getSchema();

        //convert table name
        $schema = $this->convertSchema($schema);

        $sql = "CREATE TABLE {$schema} (";

        //convert column type
        $column_definisions = (array)$this->modelData->getInfo();
        foreach ($column_definisions as $prop => $type) {
            if (!array_key_exists($type, $this->columnMap)) {
                throw new RuntimeException(
                    "unmatch O/R column map:{$prop}_{$type}"
                );
            }
            $dataType = $this->columnMap[$type];
            $sql .= "'{$prop}' {$dataType}, ";
        }

        //last conma&space delete
        $sql = mb_substr($sql, 0, mb_strlen($sql) - 2);
        $sql .= ')';
        return $sql;
    }

    /**
    *   テーブル名変換(overwrite)
    *
    *   @param string $schema
    *   @return string
    */
    protected function convertSchema(
        string $schema
    ): string {
        return $schema;
    }
}
