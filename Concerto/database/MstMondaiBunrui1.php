<?php

/**
*   mst_mondai_bunrui1
*
*   @version 200326
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
    *   @param string $cd_system
    *   @return int
    **/
    public function getMaxNo(string $cd_system)
    {
        $sql = "
            SELECT MAX(no_bunrui) AS no_bunrui
            FROM {$this->name}
            WHERE cd_system = :system
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return (isset($result[0]['no_bunrui'])) ? $result[0]['no_bunrui'] : null;
    }
}
