<?php

/**
*   tpal0010
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class Tpal0010 extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'symphony.tpal0010';

    /**
    *   年度リスト
    *
    *   @return array [[kb_nendo]]
    */
    public function getNendoList()
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT kb_nendo
                FROM
                    (SELECT 
                        CASE WHEN uriage_pday IS NULL THEN ''
                            WHEN (SUBSTR(uriage_pday, 5, 2) >= '04' 
                                AND SUBSTR(uriage_pday, 5, 2) <= '09') 
                                THEN SUBSTR(uriage_pday, 1, 4) || 'K'
                            WHEN (SUBSTR(uriage_pday, 5, 2) >= '10' 
                                AND SUBSTR(uriage_pday, 5, 2) <= '12') 
                                THEN SUBSTR(uriage_pday, 1, 4) || 'S'
                            ELSE CAST((CAST(SUBSTR(uriage_pday, 1, 4) AS INT2) - 1) AS TEXT) || 'S'
                            END AS kb_nendo 
                    FROM {$this->schema} 
                    GROUP BY uriage_pday
                    ) AS A 
                UNION
                    (SELECT 
                        CASE WHEN dt_puriage IS NULL THEN ''
                            WHEN (SUBSTR(dt_puriage, 5, 2) >= '04' 
                                AND SUBSTR(dt_puriage, 5, 2) <= '09') 
                                THEN SUBSTR(dt_puriage, 1, 4) || 'K'
                            WHEN (SUBSTR(dt_puriage, 5, 2) >= '10' 
                                AND SUBSTR(dt_puriage, 5, 2) <= '12') 
                                THEN SUBSTR(dt_puriage, 1, 4) || 'S'
                            ELSE CAST((CAST(SUBSTR(dt_puriage, 1, 4) AS INT2) - 1) AS TEXT) || 'S'
                            END AS kb_nendo 
                    FROM concerto.prospect_cyuban 
                    GROUP BY dt_puriage
                    )
                ORDER BY kb_nendo DESC
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
