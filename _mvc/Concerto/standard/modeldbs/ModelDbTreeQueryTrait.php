<?php

/**
*   ModelDbTreeQueryTrait
*
*   @version 220613
*/

declare(strict_types=1);

namespace Concerto\standard\modeldbs;

use InvalidArgumentException;
use PDOStatement ;
use Concerto\standard\ModelData;

/**
*   @template TValue
*/
trait ModelDbTreeQueryTrait
{
    /**
    *   詳細(深度・パス)取得
    *
    *   @param ModelData<TValue> $where ID
    *   @return ModelData<TValue>[]
    */
    public function detail(
        ModelData $where
    ): array {
        $sql = "
            WITH RECURSIVE
                tmp  AS (
                    SELECT * 
                        , ARRAY[{$this->primarykey}] AS path 
                    FROM {$this->name} 
                    WHERE {$this->primarykey} = :id 
                    UNION
                    SELECT B.* 
                        , B.{$this->primarykey} || path AS path 
                    FROM tmp A 
                    JOIN {$this->name} B 
                        ON B.{$this->primarykey} = A.{$this->parent} 
                            AND NOT B.{$this->primarykey} = ANY(path) 
                )
            SELECT D.*  , '/' || ARRAY_TO_STRING(C.path, '/') AS {$this->path} 
                , ARRAY_LENGTH(C.path, 1) AS {$this->depth} 
            FROM tmp C 
            LEFT JOIN {$this->name} D 
                ON D.{$this->primarykey} = :id
        ";

        if (is_null($this->root)) {
            $sql .= "
                WHERE C.{$this->parent} IS NULL
            ";
        } else {
            $sql .= "
                WHERE C.{$this->parent} = '{$this->root}' 
            ";
        }

        $stmt = $this->doExec($where, $sql);
        return (array)$stmt->fetchAll();
    }

    /**
    *   親取得
    *
    *   @param ModelData<TValue> $where ID
    *   @return ModelData<TValue>[]
    */
    public function parent(
        ModelData $where
    ): array {
        $sql = "
            SELECT * 
            FROM {$this->name} A 
            WHERE EXISTS 
                (SELECT * 
                FROM {$this->name} B 
                WHERE {$this->primarykey} = :id
                    AND B.{$this->parent} = A.{$this->primarykey}
                )
        ";

        $stmt = $this->doExec($where, $sql);
        return (array)$stmt->fetchAll();
    }

    /**
    *   子取得
    *
    *   @param ModelData<TValue> $where ID
    *   @param ?string $order ORDER句
    *   @return ModelData<TValue>[]
    *   @throws InvalidArgumentException
    */
    public function children(
        ModelData $where,
        ?string $order = null
    ): array {
        if (
            !is_null($order) &&
            !$this->isValidOrderClause($where, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        $sql = "
            SELECT * 
            FROM {$this->name} 
            WHERE {$this->parent} = :id 
        ";

        if (empty($order)) {
            $sql .= "
                ORDER BY {$this->primarykey} 
            ";
        } elseif (is_string($order)) {
            $sql .= "
                ORDER BY {$order} 
            ";
        }

        $stmt = $this->doExec($where, $sql);
        return (array)$stmt->fetchAll();
    }


    /**
    *   兄弟取得
    *
    *   @param ModelData<TValue> $where ID
    *   @param ?string $order ORDER句
    *   @return ModelData<TValue>[]
    */
    public function sibling(
        ModelData $where,
        ?string $order = null
    ): array {
        if (
            !is_null($order) &&
            !$this->isValidOrderClause($where, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        $sql = "
            SELECT * 
            FROM {$this->name} 
            WHERE {$this->parent} = 
                (SELECT {$this->parent} 
                FROM {$this->name} 
                WHERE {$this->primarykey} = :id 
                ) 
                AND {$this->primarykey} != :id 
        ";

        if (empty($order)) {
            $sql .= "
                ORDER BY {$this->primarykey} 
            ";
        } elseif (is_string($order)) {
            $sql .= "
                ORDER BY {$order} 
            ";
        }

        $stmt = $this->doExec($where, $sql);
        return (array)$stmt->fetchAll();
    }

    /**
    *   祖先取得
    *
    *   @param ModelData<TValue> $where ID
    *   @param ?string $order ORDER句
    *   @param int $limit LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return ModelData<TValue>[]
    *   @throws InvalidArgumentException
    */
    public function ancestor(
        ModelData $where,
        ?string $order = null,
        int $limit = 0
    ): array {
        if (
            !is_null($order) &&
            !$this->isValidOrderClause($where, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        if (!(is_int($limit)) || ($limit < 0)) {
            throw new InvalidArgumentException(
                "limit is integer of 0 or more"
            );
        }

        $sql = "
            WITH RECURSIVE
                tmp  AS (
                    SELECT *
                        , ARRAY[{$this->primarykey}] AS path 
                    FROM {$this->name} 
                    WHERE {$this->primarykey} = :id 
                    UNION
                    SELECT B.*
                        , B.{$this->primarykey} || path AS path 
                    FROM tmp A
                    JOIN {$this->name} B 
                        ON B.{$this->primarykey} = A.{$this->parent} 
                            AND NOT B.{$this->primarykey} = ANY(path)
                ),
                tmp_decs_rank AS (
                    SELECT *
                        , ARRAY[{$this->primarykey}] AS cycle 
                        , '/' || {$this->primarykey} AS dir 
                        , 1 AS rank 
                    FROM tmp 
        ";

        if (is_null($this->root)) {
            $sql .= "
                    WHERE cd_parent IS NULL 
            ";
        } else {
            $sql .= "
                    WHERE cd_parent = '{$this->root}' 
            ";
        }

        $sql .= "
                    UNION
                    SELECT D.*
                        , cycle || D.{$this->primarykey} AS cycle 
                        , dir || '/' || D.{$this->primarykey} AS dir 
                        , C.rank + 1 AS rank 
                    FROM tmp_decs_rank C 
                    JOIN tmp D 
                        ON D.cd_parent = C.{$this->primarykey} 
                            AND NOT D.{$this->primarykey} = ANY(cycle) 
                )
                SELECT X.* 
                    , Y.dir AS {$this->path} 
                    , Y.rank AS {$this->depth} 
                FROM {$this->name} X 
                JOIN tmp_decs_rank Y 
                    ON Y.{$this->primarykey} = X.{$this->primarykey} 
                WHERE X.{$this->primarykey} != :id 
        ";

        if ($limit > 0) {
            $sql .= "
                AND rank >= :limit 
            ";
        }

        if (empty($order)) {
            $sql .= "
                ORDER BY {$this->primarykey} 
            ";
        } elseif (is_string($order)) {
            $sql .= "
                ORDER BY {$order} 
            ";
        }

        $stmt = $this->doExecWithLimit($where, $sql, $limit);
        return (array)$stmt->fetchAll();
    }

    /**
    *   子孫取得(部分木)
    *
    *   @param ModelData<TValue> $where ID
    *   @param ?string $order ORDER句
    *   @param int $limit LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return ModelData<TValue>[]
    */
    public function descendant(
        ModelData $where,
        ?string $order = null,
        int $limit = 0
    ): array {
        if (
            !is_null($order) &&
            !$this->isValidOrderClause($where, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        if (!(is_int($limit)) || ($limit < 0)) {
            throw new InvalidArgumentException(
                "limit is integer of 0 or more"
            );
        }

        $sql = "
            WITH RECURSIVE
                tmp  AS (
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
            SELECT C.*
                , '/' || ARRAY_TO_STRING(D.path, '/') AS {$this->path} 
                , ARRAY_LENGTH(D.path, 1) - 1 AS {$this->depth} 
            FROM {$this->name} C 
            JOIN tmp D 
                ON D.{$this->primarykey} = C.{$this->primarykey} 
            WHERE C.{$this->primarykey} != :id 
        ";

        if ($limit > 0) {
            $sql .= "
                AND ARRAY_LENGTH(D.path, 1) - 1 <= :limit 
            ";
        }

        if (empty($order)) {
            $sql .= "
                ORDER BY {$this->primarykey} 
            ";
        } elseif (is_string($order)) {
            $sql .= "
                ORDER BY {$order} 
            ";
        }

        $stmt = $this->doExecWithLimit($where, $sql, $limit);
        return (array)$stmt->fetchAll();
    }

    /**
    *   ルートノード取得
    *
    *   @return ModelData<TValue>[]
    */
    public function root(): array
    {
        $sql = "
            SELECT * 
            FROM {$this->name} 
        ";
        if (is_null($this->root)) {
            $sql .= "
                WHERE {$this->parent} IS NULL 
            ";
        } else {
            $sql .= "
                WHERE {$this->parent} = '{$this->root}' 
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $class_name = $this->entityName();
        $this->decorate($stmt, $class_name);
        return (array)$stmt->fetchAll();
    }

    /**
    *   Tree取得
    *
    *   @param ModelData<TValue> $where ID
    *   @return ModelData<TValue>[]
    */
    public function treePath(
        ModelData $where
    ): array {
        $sql = "
            WITH RECURSIVE
                tmp  AS (
                    SELECT *
                        , ARRAY[{$this->primarykey}] AS path 
                    FROM {$this->name} 
                    WHERE {$this->primarykey} = :id 
                    UNION
                    SELECT B.*
                        , B.{$this->primarykey} || path AS path 
                    FROM tmp A
                    JOIN {$this->name} B 
                        ON B.{$this->primarykey} = A.{$this->parent} 
                            AND NOT B.{$this->primarykey} = ANY(path)
                ),
                tmp_decs_rank AS (
                    SELECT *
                        , ARRAY[{$this->primarykey}] AS cycle 
                        , '/' || {$this->primarykey} AS dir 
                        , 1 AS rank 
                    FROM tmp 
        ";

        if (is_null($this->root)) {
            $sql .= "
                    WHERE cd_parent IS NULL 
            ";
        } else {
            $sql .= "
                    WHERE cd_parent = '{$this->root}' 
            ";
        }

        $sql .= "
                    UNION
                    SELECT D.*
                        , cycle || D.{$this->primarykey} AS cycle 
                        , dir || '/' || D.{$this->primarykey} AS dir 
                        , C.rank + 1 AS rank 
                    FROM tmp_decs_rank C 
                    JOIN tmp D 
                        ON D.cd_parent = C.{$this->primarykey} 
                            AND NOT D.{$this->primarykey} = ANY(cycle) 
                )
                SELECT X.* 
                    , Y.dir AS {$this->path} 
                    , Y.rank AS {$this->depth} 
                FROM {$this->name} X 
                JOIN tmp_decs_rank Y 
                    ON Y.{$this->primarykey} = X.{$this->primarykey} 
                ORDER BY Y.rank 
        ";

        $stmt = $this->doExec($where, $sql);
        return (array)$stmt->fetchAll();
    }

    /**
    *   ノード探索
    *
    *   @param ModelData<TValue> $where ID
    *   @param string $sort_type 検索方向(depth:深さ優先 breadth:幅優先)
    *   @param ?string $order ORDER句
    *   @param int $limit LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return ModelData<TValue>[]
    */
    public function searchTree(
        ModelData $where,
        string $sort_type = 'depth',
        ?string $order = null,
        int $limit = 0
    ): array {
        $sql = '';
        if (
            $sort_type !== 'depth' &&
            $sort_type !== 'breadth'
        ) {
            throw new InvalidArgumentException(
                "sort type is 'depth' or 'breadth'"
            );
        }

        if (
            !is_null($order) &&
            !$this->isValidOrderClause($where, $order)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$order}"
            );
        }

        if (!(is_int($limit)) || ($limit < 0)) {
            throw new InvalidArgumentException(
                "limit is integer of 0 or more"
            );
        }

        if (is_null($order)) {
            $order = $this->primarykey;
        }

        $sql = "
            WITH RECURSIVE
                rowsorted AS (
                    SELECT *
                        , ROW_NUMBER() OVER (ORDER BY {$order}) AS rowno 
                    FROM {$this->name} 
                ),
                tmp  AS (
                    SELECT *
                        , ARRAY[{$this->primarykey}] AS path 
                        , 1 AS depth 
                        , ARRAY[rowno] AS sortarray 
                    FROM rowsorted 
                    WHERE {$this->primarykey} = :id 
                    UNION 
                    SELECT B.* 
                        , path || B.{$this->primarykey} AS path
                        , depth + 1 AS depth 
                        , sortarray || B.rowno  AS sortarray
                    FROM tmp A 
                    JOIN rowsorted B 
                        ON B.{$this->parent} = A.{$this->primarykey} 
                            AND NOT B.{$this->primarykey} = ANY(path) 
                )
            SELECT C.* 
                , '/' || ARRAY_TO_STRING(path, '/') AS {$this->path} 
                , depth AS {$this->depth} 
            FROM {$this->name} C 
            JOIN tmp D 
                ON D.{$this->primarykey} = C.{$this->primarykey} 
        ";

        if ($limit > 0) {
            $sql .= "
                WHERE depth <= :limit 
            ";
        }

        if ($sort_type === 'breadth') {
            $sql .= "
                ORDER BY {$this->depth}, sortarray 
            ";
        } else {
            $sql .= "
                ORDER BY sortarray 
            ";
        }

        $stmt = $this->doExecWithLimit($where, $sql, $limit);
        return (array)$stmt->fetchAll();
    }
}
