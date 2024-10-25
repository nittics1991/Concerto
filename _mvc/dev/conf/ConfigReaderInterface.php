<?php

/**
 *   ConfigReaderInterface
 *
 * @version 190626
 */

declare(strict_types=1);

namespace Concerto\conf;

interface ConfigReaderInterface
{
    /**
     *   読み込み
     *
     * @return array
     */
    public function read(): array;
}
