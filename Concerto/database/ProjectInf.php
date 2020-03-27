<?php

/**
*   project_inf
*
*   @version 150427
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class ProjectInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.project_inf';
    
    /**
    *   no_project最大値取得
    *
    *   @return integer
    */
    public function getMaxNoProject()
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_project) AS no_project 
                    FROM {$this->schema} 
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_project'])) ?        0 : $result['no_project'];
    }
}
