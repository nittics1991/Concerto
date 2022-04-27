<?php

/**
*   mst_bumon
*
*   @version 200323
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;

class MstBumon extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_bumon';

    /**
    *   部門リスト(注番あり部門)
    *
    *   @return array [[cd_bumon, nm_bumon], ...]
    */
    public function getCyubanBumon()
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
