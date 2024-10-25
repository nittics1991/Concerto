<?php

/**
*   mst_bumon
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class MstBumon extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mst_bumon';

    /**
    *   部門リスト(注番あり部門)
    *
    *   @return mixed[] [[cd_bumon, nm_bumon], ...]
    */
    public function getCyubanBumon(): array
    {
        $sql = "
            SELECT DISTINCT A.cd_bumon
                , A.nm_bumon
            FROM public.mst_bumon A
            JOIN public.koban_inf B
                ON B.cd_bumon = A.cd_bumon
            WHERE A.fg_cost = '1'
            ORDER BY A.cd_bumon
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
