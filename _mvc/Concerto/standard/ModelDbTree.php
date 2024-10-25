<?php

/**
*   データベーステーブルTree構造
*
*   @version 221214
*/

declare(strict_types=1);

namespace Concerto\standard;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Concerto\standard\{
    ModelData,
    ModelDb
};
use Concerto\standard\modeldbs\{
    ModelDbTreeCommandTrait,
    ModelDbTreeCounterTrait,
    ModelDbTreeDecisionTrait,
    ModelDbTreeQueryTrait,
};

/**
*   @template TValue
*/
class ModelDbTree extends ModelDb
{
    use ModelDbTreeCommandTrait;
    use ModelDbTreeCounterTrait;
    use ModelDbTreeDecisionTrait;
    use ModelDbTreeQueryTrait;

    /**
    *   @var ?string
    */
    protected ?string $root = null;

    /**
    *   @var string
    */
    protected string $primarykey = 'id';

    /**
    *   @var string
    */
    protected string $parent = 'parent';

    /**
    *   @var string
    */
    protected string $depth = 'depth';

    /**
    *   @var string
    */
    protected string $path = 'path';

    /**
    *   bind primarykey
    *
    *   @param ModelData<TValue> $obj
    *   @param PDOStatement $stmt
    *   @return PDOStatement
    */
    public function bindPrimarykey(
        ModelData $obj,
        PDOStatement $stmt
    ): PDOStatement {
        $primarykey = $this->primarykey;
        $val = $obj->$primarykey;
        $model_type = $obj->getInfo($primarykey);
        $pdo_type = $this->convertPdoParam(
            strval($model_type)
        );

        $stmt->bindParam(':id', $val, $pdo_type);
        return $stmt;
    }

    /**
    *   SQL実行
    *
    *   @param ModelData<TValue> $where ID
    *   @param string $sql SQL
    *   @return PDOStatement
    */
    protected function doExec(
        ModelData $where,
        string $sql
    ): PDOStatement {
        if ($where->isNull($this->primarykey)) {
            throw new InvalidArgumentException(
                "primary key is NULL"
            );
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt = $this->bindPrimarykey($where, $stmt);
        $stmt->execute();
        return $this->decorate($stmt, get_class($where));
    }

    /**
    *   SQL実行(limit付)
    *
    *   @param ModelData<TValue> $where ID
    *   @param string $sql SQL
    *   @param int $limit  LIMIT(レベル制限 0:無し, 1～深さ制限)
    *   @return PDOStatement
    */
    protected function doExecWithLimit(
        ModelData $where,
        string $sql,
        int $limit = 0
    ): PDOStatement {
        if ($where->isNull($this->primarykey)) {
            throw new InvalidArgumentException(
                "primary key is NULL"
            );
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
}
