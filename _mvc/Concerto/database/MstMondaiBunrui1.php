<?php

/**
*   mst_mondai_bunrui1
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstMondaiBunrui1 extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_mondai_bunrui1';

    /**
    *   getMaxNo
    *
    *   @return ?int
    */
    public function getMaxNo(): ?int
    {
        $sql = "
            SELECT MAX(no_bunrui) AS no_bunrui
            FROM {$this->name}
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? null : (int)$result;
    }
}
