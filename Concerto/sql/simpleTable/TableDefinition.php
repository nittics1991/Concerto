<?php

/**
*   Simple Table Definition
*
*   @version 170202
**/

declare(strict_types=1);

namespace Concerto\sql\simpleTable;

use RuntimeException;
use Concerto\standard\ModelData;
use Concerto\standard\ModelDb;
 
abstract class TableDefinition
{
    /**
    *   O/Rカラム対応マップ(overwrite)
    *
    *   @var array ['ModelDataType' => 'DBColumnType', ...]
    **/
    protected $columnMap = [];
    
    /**
    *   ModelDb
    *
    *   @var ModelDb
    **/
    protected $modelDb;
    
    /**
    *   ModelData
    *
    *   @var ModelData
    **/
    protected $modelData;
    
    /**
    *   __construct
    *
    *   @param ModelDb $table テーブル情報
    *   @param ModelData $colomns カラム情報
    **/
    public function __construct(ModelDb $table, ModelData $colomns)
    {
        $this->modelDb = $table;
        $this->modelData = $colomns;
    }
    
    /**
    *   CREATE TABLE
    *
    *   @return string
    **/
    public function createTable(): string
    {
        $schema = $this->modelDb->getSchema();
        
        //convert table name
        $schema = $this->convertSchema($schema);
        $sql = "CREATE TABLE {$schema} (";
        
        //convert column type
        foreach ($this->modelData->getInfo() as $prop => $type) {
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
    **/
    protected function convertSchema(string $schema): string
    {
        return $schema;
    }
}
