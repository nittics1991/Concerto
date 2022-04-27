<?php

/**
*   tsal0050
*
*   @version 190909
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class Tsal0050 extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'symphony.tsal0050';

    /**
    *   納期年月リスト
    *
    *   @return array [[kb_nendo]]
    */
    public function getDtNokiYYYYMMList()
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT DISTINCT substr(noki_day, 1, 6) AS dt_yyyymm
                    FROM {$this->schema} 
                    WHERE noki_day IS NOT NULL
                        AND noki_day != ''
                    ORDER BY substr(noki_day, 1, 6) DESC
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
