<?php

/**
*   ModelDbTreeDecisionTrait
*
*   @version 210915
*/

declare(strict_types=1);

namespace Concerto\standard\modeldbs;

use InvalidArgumentException;
use Concerto\standard\ModelData;

/**
*   @template TValue
*/
trait ModelDbTreeDecisionTrait
{
    /**
    *   カラム確認
    *
    *   @param ModelData<TValue> $obj
    *   @return bool
    */
    public function checkColumnName(
        ModelData $obj
    ): bool {
        $columns = (array)$obj->getInfo();

        return
            array_key_exists($this->primarykey, $columns) &&
            array_key_exists($this->parent, $columns) &&
            array_key_exists($this->depth, $columns) &&
            array_key_exists($this->path, $columns);
    }

    /**
    *   Leaf判定
    *
    *   @param ModelData<TValue> $where ID
    *   @return bool
    *   @throws InvalidArgumentException
    */
    public function isLeaf(
        ModelData $where
    ): bool {
        if ($where->isNull($this->primarykey)) {
            throw new InvalidArgumentException(
                "primary key is NULL"
            );
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
}
