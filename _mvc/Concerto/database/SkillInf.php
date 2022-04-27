<?php

/**
*   skill_inf
*
*   @version 160421
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class SkillInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.skill_inf';

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
            $sql = "SELECT DISTINCT kb_nendo 
                    FROM {$this->schema} 
                    ORDER BY kb_nendo DESC
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
