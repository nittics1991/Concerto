<?php

/**
*   mst_bunya_seizo
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstBunyaSeizo extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mst_bunya_seizo';

    /**
    *   no_bunya最大値取得
    *
    *   @return int
    */
    public function getMaxNo(): int
    {
        $sql = "
            SELECT MAX(no_bunya) AS no_bunya
            FROM {$this->schema}
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
    *   分野リスト
    *
    *   @param string $cd_system
    *   @return mixed[]
    */
    public function getBunyaList(
        string $cd_system
    ): array {
        $sql = "
            SELECT no_bunya, nm_bunya
            FROM {$this->schema} A
            WHERE cd_system = :system
                AND dt_delete = ''
            ORDER BY no_order, no_bunya
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
