<?php

/**
*   mst_mondai_yoin
*
*   @version 200605
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstMondaiYoin extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_mondai_yoin';

    /**
    *   getBunruiList
    *
    *   @param ?int $no_bunrui1
    *   @param ?int $no_bunrui2
    *   @return array
    */
    public function getBunruiList(
        $no_bunrui1 = null,
        $no_bunrui2 = null
    ) {
        $sql = "
            SELECT A.no_bunrui1, A.no_bunrui2, A.cd_yoin, A.nm_yoin
                , B.nm_bunrui AS nm_bunrui1
                , C.nm_bunrui AS nm_bunrui2
            FROM (
                SELECT *
                FROM {$this->name}
                ) A
            LEFT JOIN (
                SELECT *
                FROM public.mst_mondai_bunrui1
                ) B
                ON B.no_bunrui = A.no_bunrui1
            LEFT JOIN (
                SELECT *
                FROM public.mst_mondai_bunrui2
                ) C
                ON C.no_bunrui1 = A.no_bunrui1
                    AND C.no_bunrui2 = A.no_bunrui2
            WHERE 1 = 1
        ";

        if (isset($no_bunrui1)) {
            $sql .= " AND A.no_bunrui1 = :bunrui1";
        }

        if (isset($no_bunrui2)) {
            $sql .= " AND A.no_bunrui2 = :bunrui2";
        }

        $sql .= "
            ORDER BY A.no_bunrui1, A.no_bunrui2, A.cd_yoin
        ";

        $stmt = $this->pdo->prepare($sql);

        if (isset($no_bunrui1)) {
            $stmt->bindValue(':bunrui1', $no_bunrui1, PDO::PARAM_INT);
        }

        if (isset($no_bunrui2)) {
            $stmt->bindValue(':bunrui2', $no_bunrui2, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
