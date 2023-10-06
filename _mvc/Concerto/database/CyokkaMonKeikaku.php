<?php

/**
*   cyokka_mon_keikaku
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class CyokkaMonKeikaku extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.cyokka_mon_keikaku';

    /**
    *   集計リスト
    *
    *   @param ?string $kb_nendo 年度
    *   @param ?string $cd_bumon 部門
    *   @return mixed[]
    */
    public function getAggregate(
        ?string $kb_nendo = null,
        ?string $cd_bumon = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT SUM(dt_kado) AS dt_kado 
                        , SUM(tm_zitudo) AS tm_zitudo 
                        , SUM(tm_teizikan) AS tm_teijikan 
                        , SUM(tm_zangyo) AS tm_zangyo 
                        , SUM(tm_cyokka) AS tm_cyokka 
                        , SUM(tm_zitudo_m) AS tm_zitudo_m 
                        , SUM(tm_teizikan_m) AS tm_teijikan_m 
                        , SUM(tm_zangyo_m) AS tm_zangyo_m 
                        , SUM(tm_cyokka_m) AS tm_cyokka_m 
                        , SUM(yn_yosan) AS yn_yosan 
                        , SUM(yn_soneki) AS yn_soneki 
                    FROM {$this->schema} 
                    WHERE (kb_nendo = :kb_nendo
                        AND cd_bumon = :cd_bumon
                        ) IS NOT FALSE 
                    GROUP BY kb_nendo, cd_bumon
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindParam(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->bindParam(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   指定期間データ
    *
    *   @param string $cd_bumon
    *   @param string $start
    *   @param string $end
    *   @return mixed[]
    */
    public function getPeriod(
        string $cd_bumon,
        string $start,
        string $end
    ): array {
        $sql = "
            SELECT *
            FROM {$this->schema}
            WHERE cd_bumon = :bumon
                AND dt_yyyymm >= :start
                AND dt_yyyymm <= :end
            ORDER BY dt_yyyymm
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_STR);
        $stmt->bindParam(':end', $end, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   buildMonthlyByPersonLSql
    *
    *   @return string
    */
    protected function buildMonthlyByPersonLSql(): string
    {
        return "
            SELECT
                B.*, A.*
                , ROUND(
                    CAST(A.tm_zitudo_m * ri_cyokka / 100 AS NUMERIC),
                    2
                )  AS tm_cyokka_m2
            FROM public.cyokka_mon_keikaku A
            JOIN public.mst_tanto B
                ON B.cd_bumon = A.cd_bumon
            WHERE A.cd_bumon = :bumon
                AND dt_yyyymm >= :start
                AND dt_yyyymm <= :end
        ";
    }

    /**
    *   担当者別月別直課時間リスト
    *
    *   @param string $cd_bumon
    *   @param string $start
    *   @param string $end
    *   @param string $order
    *   @return mixed[]
    */
    public function getMonthlyByPersonInCyokka(
        string $cd_bumon,
        string $start,
        string $end,
        string $order = ''
    ): array {
        $sql = $this->buildMonthlyByPersonLSql();

        if (!empty($order)) {
            $sql .= " ORDER BY {$order}";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_STR);
        $stmt->bindParam(':end', $end, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   担当者別月別直課時間リスト
    *
    *   @param string $cd_bumon
    *   @param string $start
    *   @param string $end
    *   @return mixed[]
    */
    public function getMonthlyInCyokka(
        string $cd_bumon,
        string $start,
        string $end
    ): array {
        $baseSql = $this->buildMonthlyByPersonLSql();

        $sql = "
            WITH list AS (
                {$baseSql}
            )
            SELECT
                dt_yyyymm
                , SUM(tm_cyokka_m2) AS tm_cyokka_m2
            FROM list
            GROUP BY dt_yyyymm
            ORDER BY dt_yyyymm
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_STR);
        $stmt->bindParam(':end', $end, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
