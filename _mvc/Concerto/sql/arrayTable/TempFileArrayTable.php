<?php

/**
*   TempFileArrayTable
*
*   @version 211202
*/

declare(strict_types=1);

namespace Concerto\sql\arrayTable;

use PDO;
use RuntimeException;
use Concerto\sql\arrayTable\OnMemoryArrayTable;

class TempFileArrayTable extends OnMemoryArrayTable
{
    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->pdo = new PDO('sqlite:');
    }
}
