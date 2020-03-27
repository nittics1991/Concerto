<?php

/**
*   mst_skill_tanto
*
*   @version 160712
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use RuntimeException;
use Concerto\standard\ModelDb;

class MstSkillTanto extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_skill_tanto';
    
    /**
    *   採番
    *
    *   @return string ID
    *   @throws RuntimeException
    */
    public function createID()
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                SELECT MAX(cd_tanto) AS cd_tanto 
                FROM {$this->name} 
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if (count($result) == 0) {
            return "80001ITC";
        } else {
            $cd_tanto = $result[0]['cd_tanto'];
            $no = mb_substr($cd_tanto, 2, 3);
            
            if ($no >= 999) {
                throw new RuntimeException("no overflow");
            }
            
            $new_tanto = sprintf("80%03dITC", ++$no);
            return $new_tanto;
        }
    }
}
