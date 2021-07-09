<?php

/**
*   データベーステーブルTree構造
*
*   @version 210615
*
*   @see root node data, parent column & virtual column depth, path
*/

declare(strict_types=1);

namespace Concerto\standard;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use PDO;
use PDOStatement ;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;

class ModelDbTree extends ModelDb
{
    /**
    *   ルートノード親値
    *
    *   @var mixed
    */
    protected $root = null;

    /**
    *   primary key名(overwrite)
    *
    *   @var string
    */
    protected $primarykey = 'id';

    /**
    *   親カラム名
    *
    *   @var string
    */
    protected $parent = 'parent';

    /**
    *   深度カラム名
    *
    *   @var string
    */
    protected $depth = 'depth';

    /**
    *   パス名
    *
    *   @var string
    */
    protected $path = 'path';

    /**
    *   カラム確認
    *
    *   @param ModelData $obj
    *   @return bool
    */
    public function checkColumnName(ModelData $obj): bool
    {
        $columns = (array)$obj->getInfo();

        return
            array_key_exists($this->primarykey, $columns)
            && array_key_exists($this->parent, $columns)
            && array_key_exists($this->depth, $columns)
            && array_key_exists($this->path, $columns)
        ;
    }

    /**
    *   bind primarykey
    *
    *   @param ModelData $obj
    *   @param PDOStatement $stmt
    *   @return PDOStatement
    */
    public function bindPrimarykey(
        ModelData $obj,
        PDOStatement $stmt
    ) {
        $primarykey = $this->primarykey;
        $val = $obj->$primarykey;
        $model_type = $obj->getInfo($primarykey);
        $pdo_type = $this->convertPdoParam($model_type);

        $stmt->bindParam(':id', $val, $pdo_type);
        return $stmt;
    }

    /**
    *   SQL実行
    *
    *   @param ModelData $where ID
    *   @param string $sql SQL
    *   @return PDOStatement
    *   @throws InvalidArgumentException
    */
    protected function doExec(
        ModelData $where,
        string $sql
    ) {
        if ($where->isNull($this->primarykey)) {
            throw new InvalidArgumentException("primary key is NULL");
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindPrimarykey($where, $stmt);
        $stmt->execute();
        return $this->decorate($stmt, get_class($where));
    }

    /**
    *   SQL実行(limit付)
    *
    *   @param ModelData $where ID
    *   @param string $sql SQL
    *   @param int $limit  LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return PDOStatement
    *   @throws InvalidArgumentException
    */
    protected function doExecWithLimit(
        ModelData $where,
        string $sql,
        int $limit = 0
    ) {
        if ($where->isNull($this->primarykey)) {
            throw new InvalidArgumentException("primary key is NULL");
        }

        if (!(is_int($limit)) || ($limit < 0)) {
            throw new InvalidArgumentException(
                "limit is integer of 0 or more"
            );
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindPrimarykey($where, $stmt);

        if ($limit > 0) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $this->decorate($stmt, get_class($where));
    }

    /**
    *   詳細(深度・パス)取得
    *
    *   @param ModelData $where ID
    *   @return ModelData[]
    *   @throws RuntimeException
    */
    public function detail(ModelData $where)
    {
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

        try {
            $stmt = $this->doExec($where, $sql);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   親取得
    *
    *   @param ModelData $where ID
    *   @return ModelData[]
    *   @throws RuntimeException
    */
    public function parent(ModelData $where)
    {
        /**
        *   プリペア
        *
        *   @var PDOStatement
        */
        static $stmt;

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

        try {
            $stmt = $this->doExec($where, $sql);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   子取得
    *
    *   @param ModelData $where ID
    *   @param ?string $order ORDER句
    *   @return ModelData[]
    *   @throws RuntimeException, InvalidArgumentException
    */
    public function children(ModelData $where, ?string $order = null)
    {
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

        try {
            $stmt = $this->doExec($where, $sql);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }


    /**
    *   兄弟取得
    *
    *   @param ModelData $where ID
    *   @param ?string $order ORDER句
    *   @return ModelData[]
    *   @throws RuntimeException
    */
    public function sibling(ModelData $where, ?string $order = null)
    {
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

        try {
            $stmt = $this->doExec($where, $sql);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   祖先取得
    *
    *   @param ModelData $where ID
    *   @param ?string $order ORDER句
    *   @param int $limit LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return ModelData[]
    *   @throws RuntimeException, InvalidArgumentException
    */
    public function ancestor(
        ModelData $where,
        ?string $order = null,
        int $limit = 0
    ) {
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

        try {
            $stmt = $this->doExecWithLimit($where, $sql, $limit);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   子孫取得(部分木)
    *
    *   @param ModelData $where ID
    *   @param ?string $order ORDER句
    *   @param int $limit LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return ModelData[]
    *   @throws RuntimeException
    */
    public function descendant(
        ModelData $where,
        ?string $order = null,
        int $limit = 0
    ) {
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

        try {
            $stmt = $this->doExecWithLimit($where, $sql, $limit);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   子の数取得
    *
    *   @param ModelData $where ID
    *   @return int
    */
    public function numberOfChildren(ModelData $where): int
    {
        $result = $this->children($where);
        return (count($result));
    }

    /**
    *   兄弟の数取得(自分を含む)
    *
    *   @param ModelData $where ID
    *   @return int
    */
    public function numberOfSibling(ModelData $where): int
    {
        $result = $this->sibling($where);
        return (count($result) + 1);
    }

    /**
    *   Leaf判定
    *
    *   @param ModelData $where ID
    *   @return bool
    *   @throws InvalidArgumentException
    */
    public function isLeaf(ModelData $where): bool
    {
        if ($where->isNull($this->primarykey)) {
            throw new InvalidArgumentException("primary key is NULL");
        }

        $obj = clone $where;
        $obj->unsetAll();

        $id = $this->primarykey;
        $parent = $this->parent;
        $obj->$parent = $where->$id;

        $result = $this->groupBy(
            "COUNT({$this->parent}) AS {$this->depth}",
            $obj
        );

        if (isset($result[0]['no_depth'])) {
            return $result[0]['no_depth'] <= 1;
        }
        return false;
    }

    /**
    *   ルートノード取得
    *
    *   @return ModelData[]
    */
    public function root()
    {
        /**
        *   プリペア
        *
        *   @var PDOStatement
        */
        static $stmt;

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

        try {
            if (is_null($stmt)) {
                $stmt = $this->pdo->prepare($sql);
            }

            $stmt->execute();

            $class_name = $this->entityName();
            $this->decorate($stmt, $class_name);

            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }


    /**
    *   Tree取得
    *
    *   @param ModelData $where ID
    *   @return ModelData[]
    *   @throws RuntimeException
    */
    public function treePath(ModelData $where)
    {
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

        try {
            $stmt = $this->doExec($where, $sql);
            return (array)$stmt->fetchAll();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   ノード探索
    *
    *   @param ModelData $where ID
    *   @param string $sort_type 検索方向(depth:深さ優先 breadth:幅優先)
    *   @param ?string $order ORDER句
    *   @param int $limit LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return ModelData[]
    *   @throws RuntimeException
    */
    public function searchTree(
        ModelData $where,
        string $sort_type = 'depth',
        ?string $order = null,
        int $limit = 0
    ) {
        $sql = '';
        try {
            if (($sort_type != 'depth') && ($sort_type != 'breadth')) {
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

            if ($sort_type == 'breadth') {
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
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   挿入(接ぎ木)
    *
    *   @param ModelData $data
    *   @throws RuntimeException
    */
    public function graft(ModelData $data): void
    {
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

        if (is_null($this->root) && $data->isNull($this->parent)) {
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

        try {
            $stmt = $this->pdo->prepare($sql);
            $this->bind($stmt, $data);
            $stmt->execute();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   移動(付け替え)
    *
    *   @param ModelData $target 対象
    *   @param ModelData $where 移動先親
    *   @throws RuntimeException
    */
    public function move(
        ModelData $target,
        ModelData $where
    ): void {
        /**
        *   プリペア
        *
        *   @var PDOStatement
        */
        static $stmt;

        $sql = "
            UPDATE {$this->name} 
            SET {$this->parent} = :parent 
            WHERE {$this->primarykey} = :id 
        ";

        try {
            if (is_null($stmt)) {
                $stmt = $this->pdo->prepare($sql);
            }

            $stmt = $this->bindPrimarykey($target, $stmt);

            $primarykey = $this->primarykey;
            $val = $where->$primarykey;
            $model_type = $where->getInfo($primarykey);
            $pdo_type = $this->convertPdoParam($model_type);

            $stmt->bindParam(':parent', $val, $pdo_type);

            $stmt->execute();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   枝刈り
    *
    *   @param ModelData $target 対象
    *   @throws RuntimeException
    */
    public function prune(ModelData $target): void
    {
        /**
        *   プリペア
        *
        *   @var PDOStatement
        */
        static $stmt;

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

        try {
            if (is_null($stmt)) {
                $stmt = $this->pdo->prepare($sql);
            }

            $stmt = $this->bindPrimarykey($target, $stmt);
            $stmt->execute();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }

    /**
    *   枝刈り
    *
    *   @param ModelData $target 対象
    *   @throws RuntimeException
    */
    public function pull(ModelData $target): void
    {
        /**
        *   プリペア
        *
        *   @var PDOStatement
        */
        static $stmt;

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

        try {
            if (is_null($stmt)) {
                $stmt = $this->pdo->prepare($sql);
            }

            $stmt = $this->bindPrimarykey($target, $stmt);
            $stmt->execute();
        } catch (Exception $e) {
            throw new RuntimeException("PDO error:{$sql}", 0, $e);
        }
    }
}
