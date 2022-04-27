<?php

/**
*   hicyokka_inf
*
*   @version 220120
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use PDO;
use Concerto\standard\ModelDb;

class HicyokkaInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.hicyokka_inf';

    /**
    *   分類リスト
    *
    *   @param  string $cd_system
    *   @return array
    */
    public function bunruiList(
        string $cd_system,
    ): array {
        $sql = "
            SELECT DISTINCT nm_bunrui
            FROM public.hicyokka_inf A
            JOIN (
                SELECT cd_bumon
                FROM symphony.bumon_group
                WHERE cd_system = :cd_system
            ) B
                ON B.cd_bumon = A.cd_bumon
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cd_system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        $result = (array)$stmt->fetchAll();

        $hicyokkaInfData = $this->createModel();
        $fixedl_bunrui_list =
            $hicyokkaInfData->fixedBunruiList();

        $result = array_merge(
            $fixedl_bunrui_list,
            array_column($result, 'nm_bunrui'),
        );

        $uniqued = array_unique($result);
        sort($uniqued);
        return $uniqued;
    }

    /**
    *   年月リスト
    *
    *   @return array
    */
    public function yyyymmList(): array
    {
        $sql = "
            SELECT DISTINCT dt_yyyymm
            FROM public.hicyokka_inf
            ORDER BY dt_yyyymm DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   連番生成
    *
    *   @return int
    */
    public function generateNoSeq(): int
    {
        $sql = "
            SELECT MAX(no_seq) AS no_seq
            FROM public.hicyokka_inf
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchColumn();
        return empty($result) ? 1 : $result + 1;
    }
}
