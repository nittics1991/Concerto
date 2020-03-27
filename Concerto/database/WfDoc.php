<?php

/**
*   wf_doc
*
*   @version 151105
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class WfDoc extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.wf_doc';
    
    /**
    *   no_seq最大値取得
    *
    *   @param string $no_cyu 注番
    *   @param int $no_page ページ
    *   @return int
    */
    public function getMaxNoSeq($no_cyu, $no_page)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_seq) AS no_seq 
                    FROM {$this->schema} 
                    WHERE no_cyu = :no_cyu 
                    AND no_page = :no_page 
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':no_page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_seq'])) ?        0 : $result['no_seq'];
    }
}
