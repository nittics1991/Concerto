<?php

/**
*   Sigmagrid SortInfo Collection
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\sigmagrid\grid\{
    SigmagridBaseCollection,
    SigmagridSortInfo
};

class SigmagridSortInfos extends SigmagridBaseCollection
{
    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(
        array $params = []
    ) {
        $dataset = [];

        foreach ($params as $items) {
            $dataset[] = new SigmagridSortInfo(
                (array)$items
            );
        }
        parent::__construct($dataset);
    }
}
