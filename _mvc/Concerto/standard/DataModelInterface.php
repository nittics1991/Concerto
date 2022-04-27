<?php

/**
*   DataModelInterface
*
*   @version 190523
*/

declare(strict_types=1);

namespace Concerto\standard;

interface DataModelInterface
{
    /**
    *   toArray
    *
    *   @return mixed[]
    */
    public function toArray();

    /**
    *   getInfo
    *
    *   @param ?string $key
    *   @return mixed[]
    */
    public function getInfo($key = null);
}
