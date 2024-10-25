<?php

/**
*   ModelDbTreeCounterTrait
*
*   @version 210915
*/

declare(strict_types=1);

namespace Concerto\standard\modeldbs;

use Concerto\standard\ModelData;

/**
*   @template TValue
*/
trait ModelDbTreeCounterTrait
{
    /**
    *   子の数取得
    *
    *   @param ModelData<TValue> $where ID
    *   @return int
    */
    public function numberOfChildren(
        ModelData $where
    ): int {
        $result = $this->children($where);
        return (count($result));
    }

    /**
    *   兄弟の数取得(自分を含む)
    *
    *   @param ModelData<TValue> $where ID
    *   @return int
    */
    public function numberOfSibling(
        ModelData $where
    ): int {
        $result = $this->sibling($where);
        return (count($result) + 1);
    }
}
