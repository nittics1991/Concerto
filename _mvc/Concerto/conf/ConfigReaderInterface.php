<?php

/**
*   ConfigReaderInterface
*
*   @version 221206
*/

declare(strict_types=1);

namespace Concerto\conf;

interface ConfigReaderInterface
{
    /**
    *   読み込み
    *
    * @return mixed[]
    */
    public function read(): array;
}
