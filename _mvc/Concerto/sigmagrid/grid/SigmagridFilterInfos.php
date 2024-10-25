<?php

/**
*   Sigmagrid FilterInfo Collection
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\sigmagrid\grid\{
    SigmagridBaseCollection,
    SigmagridFilterInfo
};

/**
*   @template TValue
*   @extends SigmagridBaseCollection<SigmagridFilterInfo>
*/
class SigmagridFilterInfos extends SigmagridBaseCollection
{
    /**
    *   __construct
    *
    *   @param array<array<bool|int|float|string|null>> $params
    */
    public function __construct(
        array $params = []
    ) {
        $dataset = [];

        foreach ($params as $items) {
            $dataset[] = new SigmagridFilterInfo($items);
        }
        parent::__construct($dataset);
    }
}
