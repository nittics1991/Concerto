<?php

/**
*   hicyokka_inf
*
*   @version 221222
*   @phpstan-error Could not read file: true.inc
*       sort()で発生
*/

declare(strict_types=1);

namespace Concerto\database;

use DateTimeImmutable;
use InvalidArgumentException;
use PDO;
use Concerto\standard\ModelDb;

class HicyokkaInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.hicyokka_inf';

    /**
    *   分類リスト
    *
    *   @param  string $cd_system
    *   @return mixed[]
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
    *   件名リスト
    *
    *   @param  string $cd_system
    *   @return string[][]
    */
    public function nmHicyokkaList(
        string $cd_system,
    ): array {
        $sql = "
            SELECT nm_hicyokka
            FROM public.hicyokka_inf A
            JOIN symphony.bumon_group B
                ON B.cd_bumon = A.cd_bumon
            WHERE dt_yyyymm >= :dt_yyyymm
                AND cd_system = :cd_system
            GROUP BY nm_hicyokka
            ORDER BY nm_hicyokka
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cd_system', $cd_system, PDO::PARAM_STR);

        $dt_yyyymm = (new DateTimeImmutable(
            'first day of previous month today'
        ))->format('Ym');

        $stmt->bindValue(':dt_yyyymm', $dt_yyyymm, PDO::PARAM_STR);

        $stmt->execute();
        $result = (array)$stmt->fetchAll();
        return array_column($result, 'nm_hicyokka');
    }

    /**
    *   年月リスト
    *
    *   @return string[][]
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
        return empty($result) ? 1 : intval($result) + 1;
    }
}
