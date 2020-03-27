<?php

/**
*   mst_bunya_seizo
*
*   @version 200326
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstBunyaSeizo extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_bunya_seizo';
    
    /**
    *   no_bunya最大値取得
    *
    *   @param string $cd_system
    *   @return int|false|null
    */
    public function getMaxNo(string $cd_system)
    {
        $sql = "
            SELECT MAX(no_bunya) AS no_bunya
            FROM {$this->schema}
            WHERE cd_system = :system
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
    *   分野リスト
    *
    *   @param string $cd_system
    *   @return array
    **/
    public function getBunyaList(string $cd_system)
    {
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
