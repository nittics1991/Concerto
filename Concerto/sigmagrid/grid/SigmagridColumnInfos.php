<?php

/**
*   Sigmagrid ColumnInfo Collection
*
*   @version 170424
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\sigmagrid\grid\SigmagridBaseCollection;
use Concerto\sigmagrid\grid\SigmagridColumnInfo;

class SigmagridColumnInfos extends SigmagridBaseCollection
{
    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(array $params = [])
    {
        $dataset = [];

        foreach ($params as $items) {
            $dataset[] = new SigmagridColumnInfo($items);
        }
        parent::__construct($dataset);
    }
}
