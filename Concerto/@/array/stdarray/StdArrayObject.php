<?php

/**
*   StdArrayObject
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\array\stdarray;

use Concerto\array\stdarray\{
    ArrayCaseTrait,
    ArrayConbineTrait,
    ArrayCountTrait,
};

class StdArrayObject
{
    use ArrayCaseTrait;
    use ArrayConbineTrait;
    use ArrayCountTrait;
    
    
    /**
    *   dataset
    *
    *   @var array
    */
    protected $dataset;
    
    /**
    *   __construct
    *
    *   @param ?array $data
    */
    public function __construct(
        ?array $data
    ) {
        $this->dataset = $data?? [];
    }
    
    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(
    ): array {
        return $this->dataset;
    }
}
