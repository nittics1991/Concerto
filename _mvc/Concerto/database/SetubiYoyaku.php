<?php

/**
*   setubi_yoyaku
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\database\SetubiYoyakuData;
use Concerto\standard\ModelDb;

class SetubiYoyaku extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.setubi_yoyaku';

    /**
    *   isDuplicated
    *
    *   @param SetubiYoyakuData $where
    *   @return bool
    */
    public function isDuplicated(
        SetubiYoyakuData $where
    ): bool {
        $sql = "
            SELECT COUNT(*) AS cnt
            FROM {$this->schema}
            WHERE cd_setubi = :setubi
                AND (
                    (dt_start <= :start AND :start <= dt_end)
                    OR
                    (dt_start <= :end AND :end <= dt_end)
                    OR
                    (:start <= dt_start AND dt_end <= :end)
                    )
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':setubi', $where->cd_setubi, PDO::PARAM_STR);
        $stmt->bindValue(':start', $where->dt_start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $where->dt_end, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['cnt'] === 0;
    }
}
