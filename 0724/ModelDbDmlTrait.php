<?php

/**
*   ModelDb DML trait
*
*   @version 191017
*/

declare(strict_types=1);

namespace Concerto\standard;

use InvalidArgumentException;
use Concerto\standard\DataModelInterface;

trait ModelDbDmlTrait
{
    /**
    *   SELECT
    *
    *   @param DataModelInterface $where
    *   @param ?string $order
    *   @param ?int $limit
    *   @param ?int $offset
    *   @return mixed
    */
    public function select(
        DataModelInterface $where,
        ?string $order = null,
        ?int $limit = null,
        ?int $offset = null
    ) {
        if (!$this->isValidOrderClause($where, $order)) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }
        
        $sql = "SELECT * FROM {$this->table_name} WHERE 1 = 1 ";
        
        foreach ($where as $key => $val) {
            if (!is_null($val)) {
                $sql .= "AND {$key} = :{$key} ";
            }
        }
        
        if (isset($offset)) {
            $sql .= " OFFSET {$offset}";
        }
        
        if (isset($limit)) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        $this->bind($stmt, $where);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
    
    /**
    *   INSERT
    *
    *   @param DataModelInterface|DataModelInterface[] $dataset
    */
    public function insert($dataset)
    {
        if ($dataset instanceof DataModelInterface::class) {
            $dataset = [$dataset];
        }
        
        $placeholders_all = [];
        $sql_values = '';
        $i = 0;
        
        foreach ($dataset as $modelData) {
            if (!$modelData instanceof DataModelInterface) {
                throw new InvalidArgumentException(
                    "must be DataModelInterface"
                );
            }
            
            $placeholders = [];
            foreach ($modelData as $prop_name => $val) {
                $placeholders[":{$i}{$prop_name}"] = $val;
            }
            
            $placeholders_all[] = $placeholders;
            $sql_values .= sprintf(", (%s)", array_keys($placeholders));
            $i++;
        }
        
        $sql_values = mb_substr($sql_values, 1);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s) ",
            $this->table_name,
            implode(',', ($dataset[0])->getInfo()),
            $sql_values
        );
        
        $stmt = $this->pdo->prepare($sql);
        
        foreach ($placeholders_all as $placeholders) {
            $this->bind($stmt, $placeholders);
        }
        $stmt->execute();
    }
    
    /**
    *   UPDATE
    *
    *   @param array $dataset [[ModelData 保存値,  ModelData 条件], ...]
    *   @throws InvalidArgumentException
    */
    public function update($dataset)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        /**
        *   データ前回値
        *
        *   @var array
        */
        static $data_old;
        
        /**
        *   WHERE前回値
        *
        *   @var array
        */
        static $where_old;
        
        if (!is_array($dataset)) {
            throw new InvalidArgumentException("not Array");
        }
        
        $count = 0;
        $stmt = null;
        $sql = '';
        
        foreach ($dataset as $list) {
            if (!is_array($list) || count($list) != 2) {
                throw new InvalidArgumentException(
                    "inner array not Array"
                );
            }
            
            $obj = $list[0];
            $where = $list[1];
            
            if (
                !($obj instanceof DataModelInterface) ||
                !($where instanceof DataModelInterface)
            ) {
                throw new InvalidArgumentException("data type different");
            }
            
            $data_key = array_keys($obj->toArray());
            $where_key = array_keys($where->toArray());
            
            if (
                $data_key != $data_old ||
                $where_key != $where_old ||
                empty($stmt)
            ) {
                $sql = "UPDATE {$this->name} SET ";
                
                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $sql .= " {$key} = :{$key},";
                    }
                }
                
                $sql = mb_substr($sql, 0, (mb_strlen($sql) - 1));
                
                if (!empty($where_key)) {
                    $sql .= ' WHERE 1 = 1 ';
                    foreach ($where->toArray() as $key => $val) {
                        if (!is_null($val)) {
                            $sql .= "AND {$key} = :_{$key} ";
                        }
                    }
                }
                
                $stmt = $this->pdo->prepare($sql);
                $data_old = $data_key;
                $where_old = $where_key;
            }
            
            $this->bind($stmt, $obj);
            $this->bind($stmt, $where, ':_');
            $stmt->execute();
            $count++;
        }
    }
    
    /**
    *   DELETE
    *
    *   @param array $dataset 条件 [ModelData1, ModelData2, ...]
    *   @throws InvalidArgumentException
    */
    public function delete($dataset)
    {
        if (!is_array($dataset)) {
            throw new InvalidArgumentException("not Array");
        }
        
        $sql = "DELETE FROM {$this->name} ";
        $flg = false;
        $binds = [];
        $i = 0;
        
        foreach ($dataset as $obj) {
            if (!$obj instanceof DataModelInterface) {
                throw new InvalidArgumentException("data type different");
            }
            
            if (!$flg) {
                $sql .= 'WHERE ';
                $flg = true;
            } else {
                $sql .= 'OR ';
            }
            
            $fields = [];
            
            foreach ($obj->toArray() as $key => $val) {
                if (!is_null($val)) {
                    $fields[] = "{$key} = :{$i}{$key}";
                }
            }
            
            $sql .= sprintf("(%s) ", implode(' AND ', $fields));
            $binds[] = $obj;
            $i++;
        }
        
        $stmt = $this->pdo->prepare($sql);
        $j = 0;
        
        foreach ($binds as $obj1) {
            $this->bind($stmt, $obj1, ":{$j}");
            $j++;
        }
        $stmt->execute();
    }
    
    /**
    *   TRUNCATE
    *
    */
    public function truncate()
    {
        $sql = "truncate {$this->name}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }
}
