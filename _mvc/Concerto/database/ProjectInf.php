<?php

/**
*   project_inf
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class ProjectInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.project_inf';

    /**
    *   no_project最大値取得
    *
    *   @return int
    */
    public function getMaxNoProject(): int
    {
        /**
        *   @var ?\PDOStatement
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

    /**
    *   deleteCyuban
    *
    *   @param string $no_cyu
    *   @return void
    */
    public function deleteCyuban(
        string $no_cyu,
    ): void {
        $sql = "
            DELETE FROM public.project_inf
            WHERE no_project IN
                (SELECT no_project
                FROM public.project_cyuban A
                WHERE EXISTS
                    (SELECT no_project
                    FROM public.project_cyuban B
                    WHERE no_cyu = :no_cyu
                        AND B.no_project = A.no_project
                    )
                GROUP BY no_project
                HAVING COUNT(*) = 1
                )
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':no_cyu',
            $no_cyu,
            PDO::PARAM_STR,
        );

        $stmt->execute();
    }
}
