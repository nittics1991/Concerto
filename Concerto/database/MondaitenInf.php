<?php

/**
*   mondaiten_inf
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MondaitenInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mondaiten_inf';

    /**
    *   getMaxNo
    *
    *   @param string $no_cyu
    *   @param int $no_page
    *   @return ?int
    */
    public function getMaxNo($no_cyu, $no_page): ?int
    {
        $sql = "
            SELECT MAX(no_seq) AS no_seq
            FROM {$this->name}
            WHERE no_cyu = :cyuban
                AND no_page = :page
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? null : (int)$result;
    }

    /**
    *   getByCyuban
    *
    *   @param string $no_cyu
    *   @param int|null $no_page
    *   @return array
    */
    public function getByCyuban(
        $no_cyu,
        $no_page = null
    ) {
        $sql = "
            SELECT A.*
                , B. cd_yoin AS cd_bunrui3, B. nm_yoin AS nm_bunrui3
                , C. no_bunrui2 AS cd_bunrui2, C. nm_bunrui AS nm_bunrui2
                , D. no_bunrui AS cd_bunrui1, D. nm_bunrui AS nm_bunrui1
            FROM 
                (SELECT *
                FROM {$this->name}
                WHERE (no_cyu = :cyuban
                    AND no_page = :page
                    ) IS NOT FALSE
                ) A
            LEFT JOIN public.mst_mondai_yoin B
                ON B.cd_yoin = A.cd_bunrui
            LEFT JOIN public.mst_mondai_bunrui2 C
                ON C.no_bunrui2 = B.no_bunrui2
                    AND C.no_bunrui1 = B.no_bunrui1
            LEFT JOIN public.mst_mondai_bunrui1 D
                ON D.no_bunrui = B.no_bunrui1
            ORDER BY A.no_seq
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
