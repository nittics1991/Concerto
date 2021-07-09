<?php

/**
*   setubi_yoyaku
*
*   @version 180126
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;
use Concerto\database\SetubiYoyakuData;

class SetubiYoyaku extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.setubi_yoyaku';

    /**
    *   isDuplicated
    *
    *   @param SetubiYoyakuData $where
    *   @return bool
    */
    public function isDuplicated(SetubiYoyakuData $where)
    {
        $sql = "
            SELECT COUNT(*) AS cnt
            FROM {$this->schema}
            WHERE cd_setubi = :setubi
                AND (
                    (s_date <= :start AND :start <= e_date)
                    OR
                    (s_date <= :end AND :end <= e_date)
                    OR
                    (:start <= s_date AND e_date <= :end)
                    )
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':setubi', $where->cd_setubi, PDO::PARAM_STR);
        $stmt->bindValue(':start', $where->s_date, PDO::PARAM_STR);
        $stmt->bindValue(':end', $where->e_date, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['cnt'] == 0;
    }
}
