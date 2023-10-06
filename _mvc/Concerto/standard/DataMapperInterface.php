<?php

/**
*   DataMapperInterface
*
*   @version 221214
*/

declare(strict_types=1);

namespace Concerto\standard;

interface DataMapperInterface
{
    /**
    *   select
    *
    *   @param DataModelInterface $where
    *   @param ?string $order
    */
    public function select(
        DataModelInterface $where,
        ?string $order
    );

    /**
    *   insert
    *
    *   @param mixed[] $dataset
    */
    public function insert(array $dataset);

    /**
    *   update
    *
    *   @param mixed[] $dataset
    */
    public function update(array $dataset);

    /**
    *   delete
    *
    *   @param mixed[] $dataset
    */
    public function delete(array $dataset);

    /**
    *   Entityクラス生成
    *
    *   @return DataModelInterface
    */
    public function createModel(): DataModelInterface;
}
