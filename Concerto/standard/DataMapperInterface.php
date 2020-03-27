<?php

/**
*   DataMapperInterface
*
*   @ver 190524
*/

namespace Concerto\standard;

interface DataMapperInterface
{
    /**
    *   select
    *
    *   @param DataModelInterface $where
    *   @param ?string $order
    **/
    public function select(DataModelInterface $where, ?string $order);
    
    /**
    *   insert
    *
    *   @param mixed $dataset
    **/
    public function insert($dataset);
    
    /**
    *   update
    *
    *   @param mixed $dataset
    **/
    public function update($dataset);
    
    /**
    *   delete
    *
    *   @param mixed $dataset
    **/
    public function delete($dataset);
}
