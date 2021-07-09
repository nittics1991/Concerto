<?php

/**
*   OnMemoryArrayTable
*
*   @version 210526
*/

declare(strict_types=1);

namespace Concerto\sql\arrayTable;

use PDO;
use Concerto\sql\arrayTable\PDOArrayTable;

class OnMemoryArrayTable extends PDOArrayTable
{

    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->pdo = new PDO('sqlite::memory:');
    }
}
