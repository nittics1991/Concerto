<?php

/**
*   DataModelInterface
*
*   @version 230926
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
    public function toArray(): array;

    /**
    *   getInfo
    *
    *   @param ?string $key
    *   @return string[]|string
    */
    public function getInfo(
        ?string $key = null
    ): array|string;
}
