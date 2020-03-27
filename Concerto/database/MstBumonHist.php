<?php

/**
*   mst_bumon_hist
*
*   @version 200324
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;
use Concerto\FiscalYear;

class MstBumonHist extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_bumon_hist';
    
    /**
    *   年度で取得
    *
    *   @param string $cd_system
    *   @param string $kb_nendo
    *   @return array
    */
    public function byNendo(string $cd_system, string $kb_nendo): array
    {
        $sql = "
            SELECT A.*
            FROM {$this->schema} A
            JOIN (
                SELECT cd_bumon
                FROM symphony.bumon_group
                WHERE cd_system = :system
            ) B
                ON B.cd_bumon = A.cd_bumon
            WHERE dt_end >= :dt
                AND dt_start <= :dt
            ORDER BY cd_bumon
        ";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        
        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        //年度末月の初日(最終では次年度の可能性がある為)
        $stmt->bindValue(':dt', "{$dt_yyyymm[5]}01", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
