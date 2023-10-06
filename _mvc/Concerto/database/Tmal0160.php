<?php

/**
*   tmal0160
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class Tmal0160 extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'symphony.tmal0160';

    /**
    *   予算機種リスト
    *
    *   @return mixed[]
    */
    public function getKisyuList(): array
    {
        $sql = "
            SELECT A.cd_kisyu
                , B.kisyu_name AS nm_kisyu
            FROM (
                SELECT DISTINCT cd_kisyu
                FROM public.cyuban_inf
                ) A
            JOIN symphony.tmal0160 B
                ON B.kisyu_cd = A.cd_kisyu
            ORDER BY A.cd_kisyu
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
