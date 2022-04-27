<?php

/**
*   ModelDbTreeCommandTrait
*
*   @version 220209
*/

declare(strict_types=1);

namespace Concerto\standard\modeldb;

use PDOStatement ;
use Concerto\standard\ModelData;

trait ModelDbTreeCommandTrait
{
    /**
    *   挿入(接ぎ木)
    *
    *   @param ModelData $data
    *   @return void
    */
    public function graft(
        ModelData $data
    ): void {
        $sql_tmp = "
            WITH
                tmp AS (
                    INSERT INTO {$this->name} 
                        (%s) 
                    VALUES
                        (%s) 
                    RETURNING *
                )
            UPDATE {$this->name} A 
            SET {$this->parent} = 
                (SELECT {$this->primarykey} FROM tmp B 
        ";

        if (
            is_null($this->root) &&
            $data->isNull($this->parent)
        ) {
            $sql_tmp .= "
                    WHERE B.{$this->parent} IS NULL 
                    ) 
                WHERE {$this->parent} IS NULL 
            ";
        } else {
            $sql_tmp .= "
                    WHERE B.{$this->parent} = A.{$this->parent} 
                    ) 
                WHERE {$this->parent} IN 
                    (SELECT {$this->parent} FROM tmp) 
            ";
        }

        $columns = [];
        $values = [];

        foreach ($data->toArray() as $key => $val) {
            if (!is_null($val)) {
                $columns[] = $key;
                $values[] = ":{$key}";
            }
        }

        $sql = sprintf(
            $sql_tmp,
            implode(',', $columns),
            implode(',', $values)
        );

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $data);
        $stmt->execute();
    }

    /**
    *   移動(付け替え)
    *
    *   @param ModelData $target 対象
    *   @param ModelData $where 移動先親
    *   @return void
    */
    public function move(
        ModelData $target,
        ModelData $where
    ): void {
        $sql = "
            UPDATE {$this->name} 
            SET {$this->parent} = :parent 
            WHERE {$this->primarykey} = :id 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindPrimarykey($target, $stmt);

        $primarykey = $this->primarykey;
        $val = $where->$primarykey;
        $model_type = $where->getInfo($primarykey);
        $pdo_type = $this->convertPdoParam($model_type);

        $stmt->bindParam(':parent', $val, $pdo_type);
        $stmt->execute();
    }

    /**
    *   枝刈り
    *
    *   @param ModelData $target 対象
    *   @return void
    */
    public function prune(
        ModelData $target
    ): void {
        $sql = "
            WITH RECURSIVE
                tmp AS (
                    SELECT *
                        , ARRAY[{$this->primarykey}] AS path 
                    FROM {$this->name} 
                    WHERE {$this->primarykey} = :id 
                    UNION
                    SELECT B.*
                        , path || B.{$this->primarykey} AS path
                    FROM tmp A
                    JOIN {$this->name} B 
                        ON B.{$this->parent} = A.{$this->primarykey}
                            AND NOT B.{$this->primarykey} = ANY(path)
                )
            DELETE 
            FROM {$this->name} X 
            WHERE EXISTS 
                (SELECT * 
                FROM tmp Y 
                WHERE Y.{$this->primarykey} = X.{$this->primarykey} 
                )
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindPrimarykey($target, $stmt);
        $stmt->execute();
    }

    /**
    *   枝刈り
    *
    *   @param ModelData $target 対象
    *   @return void
    */
    public function pull(
        ModelData $target
    ): void {
        $sql = "
            WITH
                tmp AS ( 
                    DELETE 
                    FROM {$this->name} 
                    WHERE {$this->primarykey} = :id 
                    RETURNING * 
                ) 
            UPDATE {$this->name} 
            SET {$this->parent} = 
                (SELECT {$this->parent} FROM tmp) 
            WHERE cd_parent IN 
                (SELECT {$this->primarykey} FROM tmp) 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindPrimarykey($target, $stmt);
        $stmt->execute();
    }
}
