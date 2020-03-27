<?php

/**
*   mst_mondai_bunrui2
*
*   @version 170712
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstMondaiBunrui2 extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_mondai_bunrui2';
    
    /**
    *   getMaxNo
    *
    *   @param string $cd_system
    *   @param int $no_bunrui1
    *   @return int
    */
    public function getMaxNo(
        string $cd_system,
        int $no_bunrui1
    ) {
        $sql = "
            SELECT MAX(no_bunrui2) AS no_bunrui
            FROM {$this->name}
            WHERE no_bunrui1 = :bunrui
                AND cd_system = :system
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':bunrui', (int)$no_bunrui1, PDO::PARAM_INT);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return (isset($result[0]['no_bunrui'])) ? $result[0]['no_bunrui'] : null;
    }
    
    /**
    *   getBunruiList
    *
    *   @param string $cd_system
    *   @param int|null $no_bunrui1
    *   @return array
    */
    public function getBunruiList($no_bunrui1 = null)
    {
        $sql = "
            SELECT A.no_bunrui1, A.no_bunrui2, A.nm_bunrui AS nm_bunrui2
                , B.nm_bunrui AS nm_bunrui1
            FROM {$this->name} A
            LEFT JOIN public.mst_mondai_bunrui1 B
                ON B.no_bunrui = A.no_bunrui1
            WHERE 1 = 1
                AND cd_system = :system
        ";
        
        if (isset($no_bunrui1)) {
            $sql .= " AND A.no_bunrui1 = :bunrui1";
        }
        
        $sql .= "
            ORDER BY A.no_bunrui1, A.no_bunrui2
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        
        if (isset($no_bunrui1)) {
            $stmt->bindValue(':bunrui1', $no_bunrui1, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
