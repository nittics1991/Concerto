<?php

/**
*   cyuban_bunya
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class CyubanBunya extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.cyuban_bunya';

    /**
    *   振替(実製番昇格)
    *
    *   @param string $no_cyu_src
    *   @param string $no_cyu_target
    *   @return void
    */
    public function transfer(
        string $no_cyu_src,
        string $no_cyu_target,
    ): void {
        $sql = "
            UPDATE public.cyuban_bunya
            SET no_cyu = :no_cyu_target
            WHERE no_cyu = :no_cyu_src
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':no_cyu_src',
            $no_cyu_src,
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':no_cyu_target',
            $no_cyu_target,
            PDO::PARAM_STR
        );

        $stmt->execute();
    }
}
