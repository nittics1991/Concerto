<?php

/**
*   project_inf
*
*   @version 210608
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
    *   @return int
    */
    public function getMaxNoProject()
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_project) AS no_project 
                    FROM {$this->schema} 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }
}
