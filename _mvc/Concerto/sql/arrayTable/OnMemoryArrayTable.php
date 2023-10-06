<?php

/**
*   OnMemoryArrayTable
*
*   @version 211202
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

    /**
    *   情報スキーマcolumns
    *
    *   @param ?string $table_name
    *   @return mixed[]
    */
    public function schemaColumns(
        ?string $table_name = null
    ): array {
        $sql = is_null($table_name) ?
            "select * from sqlite_master" :
            "pragma table_info({$table_name})";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
