<?php

/**
*   skill_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class SkillInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.skill_inf';

    /**
    *   年度リスト
    *
    *   @return string[]
    */
    public function getNendoList(): array
    {
        /**
        *   @var ?\PDOStatement
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
