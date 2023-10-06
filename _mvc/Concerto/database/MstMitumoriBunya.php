<?php

/**
*   mst_mitumori_bunya
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstMitumoriBunya extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mst_mitumori_bunya';

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
            SELECT id_mitumori_bunya AS no_bunya,
                nm_bunya
            FROM {$this->schema}
            WHERE cd_system = :system
            ORDER BY id_mitumori_bunya
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
