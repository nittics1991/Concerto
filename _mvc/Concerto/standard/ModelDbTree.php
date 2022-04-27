<?php

/**
*   データベーステーブルTree構造
*
*   @version 210915
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
use Concerto\standard\modeldb\{
    ModelDbTreeCommandTrait,
    ModelDbTreeCounterTrait,
    ModelDbTreeDecisionTrait,
    ModelDbTreeQueryTrait,
};

class ModelDbTree extends ModelDb
{
    use ModelDbTreeCommandTrait;
    use ModelDbTreeCounterTrait;
    use ModelDbTreeDecisionTrait;
    use ModelDbTreeQueryTrait;

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
    *   bind primarykey
    *
    *   @param ModelData $obj
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
