<?php

/**
*   cyokka_keikaku
*
*   @version 201209
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\FiscalYear;

class ClaimKihon extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.claim_kihon';

    /**
    *   先期残数
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return int
    */
    public function lastFiscalMonthCount($kb_nendo, $cd_bumon)
    {
        $sql = "
            SELECT COALESCE(COUNT(A.*), 0) AS cnt
            FROM public.claim_kihon A
            WHERE (
                kb_group = :bumon
                AND dt_hassei < :start
                AND (dt_kakunin = '' OR dt_kakunin >= :start)
            ) IS NOT FALSE
        ";

        $stmt = $this->pdo->prepare($sql);
        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        if (empty($dt_yyyymm)) {
            $dt_yyyymm = array_fill(0, 6, '');
        }

        $start = mb_substr($dt_yyyymm[0], 0, 4) . '/' .
            mb_substr($dt_yyyymm[0], 4, 2);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
    *   新規件数集計
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    */
    public function occurenceClaimSummary($kb_nendo, $cd_bumon)
    {
        $sql = "
            SELECT SUBSTR(dt_hassei, 1, 4) || SUBSTR(dt_hassei, 6, 2) AS dt_hassei
                , COUNT(dt_hassei) AS hassei
            FROM public.claim_kihon
            WHERE (
                kb_group = :bumon
                AND dt_hassei >= :start
                AND dt_hassei <= :end
                ) IS NOT FALSE
            GROUP BY SUBSTR(dt_hassei, 1, 4) || SUBSTR(dt_hassei, 6, 2)
            ORDER BY dt_hassei
        ";

        $stmt = $this->pdo->prepare($sql);
        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        if (empty($dt_yyyymm)) {
            $dt_yyyymm = array_fill(0, 6, '');
        }

        $start = $this->formatStartDate($dt_yyyymm[0]);
        $end = $this->formatEndDate($dt_yyyymm[5]);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $end, PDO::PARAM_STR);
        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();

        $result = array_fill(0, 6, 0);

        foreach ($stmt as $list) {
            foreach ($dt_yyyymm as $no => $yyyymm) {
                if ($yyyymm == $list['dt_hassei']) {
                    $result[$no] = (int)$list['hassei'];
                }
            }
        }
        return $result;
    }

    /**
    *   処置済集計
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    */
    public function processedClaimSummary($kb_nendo, $cd_bumon)
    {
        $sql = "
            SELECT SUBSTR(dt_kakunin, 1, 4) || SUBSTR(dt_kakunin, 6, 2) AS dt_kakunin
                , COUNT(dt_kakunin) AS no_count
                , SUM(tm_kousu) AS tm_cyokka
                , SUM(yn_keihi) AS yn_keihi
                , SUM(tm_kousu * yn_tanka) AS yn_cyokka
            FROM public.claim_kihon
            WHERE (
                    kb_group = :bumon
                    AND dt_kakunin >= :start
                    AND dt_kakunin <= :end
                ) IS NOT FALSE
            GROUP BY SUBSTR(dt_kakunin, 1, 4) || SUBSTR(dt_kakunin, 6, 2)
            ORDER BY dt_kakunin
        ";

        $stmt = $this->pdo->prepare($sql);
        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        if (empty($dt_yyyymm)) {
            $dt_yyyymm = array_fill(0, 6, '');
        }

        $start = $this->formatStartDate($dt_yyyymm[0]);
        $end = $this->formatEndDate($dt_yyyymm[5]);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $end, PDO::PARAM_STR);
        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();

        $result = [];
        for ($i = 0; $i < count($dt_yyyymm); $i++) {
            $result[$i]['no_count'] = 0;
            $result[$i]['tm_cyokka'] = 0;
            $result[$i]['yn_cyokka'] = 0;
            $result[$i]['yn_keihi'] = 0;
        }

        foreach ($stmt as $list) {
            foreach ($dt_yyyymm as $no => $yyyymm) {
                if ($yyyymm == $list['dt_kakunin']) {
                    $result[$no]['no_count'] = (int)$list['no_count'];
                    $result[$no]['tm_cyokka'] = (int)$list['tm_cyokka'];
                    $result[$no]['yn_cyokka'] = (int)$list['yn_cyokka'];
                    $result[$no]['yn_keihi'] = (int)$list['yn_keihi'];
                }
            }
        }
        return $result;
    }

    /**
    *   formatStartDate
    *
    *   @param string $dt_yyyymm
    *   @return string
    */
    protected function formatStartDate($dt_yyyymm)
    {
        return mb_substr($dt_yyyymm, 0, 4) . '/' .
            mb_substr($dt_yyyymm, 4, 2) . '/01';
    }

    /**
    *   formatEndDate
    *
    *   @param string $dt_yyyymm
    *   @return string
    */
    protected function formatEndDate($dt_yyyymm)
    {
        return mb_substr($dt_yyyymm, 0, 4) . '/' .
            mb_substr($dt_yyyymm, 4, 2) . '/31';
    }
}
