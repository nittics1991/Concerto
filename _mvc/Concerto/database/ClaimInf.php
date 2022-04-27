<?php

/**
*   claim_inf
*
*   @version 201222
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\FiscalYear;

class ClaimInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.claim_inf';

    /**
    *   年度リスト
    *
    *   @return array
    */
    public function getNendoList(): array
    {
        $sql = "
            SELECT DISTINCT kb_nendo
            FROM {$this->schema}
            ORDER BY kb_nendo DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   期間内クレーム新規
    *
    *   @param ?string $kb_nendo
    *   @param ?string $cd_bumon
    *   @return array
    */
    public function getNewClaimList(
        ?string $kb_nendo = null,
        ?string $cd_bumon = null
    ): array {
        if (!isset($kb_nendo)) {
            return array_fill(0, 6, 0);
        }

        $sql = "
            SELECT
                SUBSTR(dt_hassei, 1, 6) dt_yyyymm,
                COUNT(*) AS cnt
            FROM public.claim_inf
            WHERE SUBSTR(dt_hassei, 1, 6) >= :dt_start
                AND SUBSTR(dt_hassei, 1, 6) <= :dt_end
        ";

        if (isset($cd_bumon)) {
            $sql .= " AND cd_bumon = :cd_bumon";
        }

        $sql .= "
            GROUP BY SUBSTR(dt_hassei, 1, 6)
            ORDER BY SUBSTR(dt_hassei, 1, 6)
        ";

        $stmt = $this->pdo->prepare($sql);

        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindValue(':dt_start', $dt_yyyymm[0] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':dt_end', $dt_yyyymm[5] ?? '', PDO::PARAM_STR);

        if (isset($cd_bumon)) {
            $stmt->bindValue(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = (array)$stmt->fetchAll();

        if (count($result) === 0) {
            return array_fill(0, 6, 0);
        }

        $aggs = array_column($result, 'cnt', 'dt_yyyymm');
        $items = [];

        foreach ($dt_yyyymm as $yyyymm) {
            $items[] = isset($aggs[$yyyymm]) ?
                $aggs[$yyyymm] : 0;
        }
        return $items;
    }

    /**
    *   期間内クレーム完了
    *
    *   @param ?string $kb_nendo
    *   @param ?string $cd_bumon
    *   @return array
    */
    public function getFinishClaimList(
        ?string $kb_nendo = null,
        ?string $cd_bumon = null
    ): array {
        if (!isset($kb_nendo)) {
            return array_fill(0, 6, 0);
        }

        $sql = "
            SELECT
                SUBSTR(dt_kakunin, 1, 6) dt_yyyymm,
                COUNT(*) AS cnt
            FROM public.claim_inf
            WHERE SUBSTR(dt_kakunin, 1, 6) >= :dt_start
                AND SUBSTR(dt_kakunin, 1, 6) <= :dt_end
        ";

        if (isset($cd_bumon)) {
            $sql .= " AND cd_bumon = :cd_bumon";
        }

        $sql .= "
            GROUP BY SUBSTR(dt_kakunin, 1, 6)
            ORDER BY SUBSTR(dt_kakunin, 1, 6)
        ";

        $stmt = $this->pdo->prepare($sql);

        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindValue(':dt_start', $dt_yyyymm[0] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':dt_end', $dt_yyyymm[5] ?? '', PDO::PARAM_STR);

        if (isset($cd_bumon)) {
            $stmt->bindValue(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = (array)$stmt->fetchAll();

        if (count($result) === 0) {
            return array_fill(0, 6, 0);
        }

        $aggs = array_column($result, 'cnt', 'dt_yyyymm');
        $items = [];

        foreach ($dt_yyyymm as $yyyymm) {
            $items[] = isset($aggs[$yyyymm]) ?
                $aggs[$yyyymm] : 0;
        }
        return $items;
    }

    /**
    *   先期残数
    *
    *   @param ?string $kb_nendo
    *   @param ?string $cd_bumon
    *   @return int
    */
    public function getZenkiZansu(
        ?string $kb_nendo = null,
        ?string $cd_bumon = null
    ): int {
        if (!isset($kb_nendo)) {
            return 0;
        }

        $sql = "
            SELECT COUNT(*) AS cnt
            FROM public.claim_inf
            WHERE dt_hassei < :dt_yyyymmdd
                AND (dt_kakunin = '' OR dt_kakunin >= :dt_yyyymmdd)
        ";

        if (isset($cd_bumon)) {
            $sql .= " AND cd_bumon = :cd_bumon";
        }

        $stmt = $this->pdo->prepare($sql);

        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $dt_yyyymm[0] = $dt_yyyymm[0] ?? '';
        $stmt->bindValue(':dt_yyyymmdd', "{$dt_yyyymm[0]}01", PDO::PARAM_STR);

        if (isset($cd_bumon)) {
            $stmt->bindValue(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn(0);
    }

    /**
    *   期間内費用リスト
    *
    *   @param ?string $kb_nendo
    *   @param ?string $cd_bumon
    *   @return array [[yn_yusyo, yn_syoryaku]]
    */
    public function getHiyoList(
        ?string $kb_nendo = null,
        ?string $cd_bumon = null
    ): array {

        $func_init = function () {
            $items = [];

            for ($i = 0; $i < 6; $i++) {
                $items[] = [
                    'yn_yusyo' => 0,
                    'yn_syoryaku' => 0,
                ];
            }
            return $items;
        };

        if (!isset($kb_nendo)) {
            return $func_init();
        }

        $sql = "
            SELECT
                SUBSTR(dt_kakunin, 1, 6) dt_yyyymm,
                SUM(yn_yusyo) AS yn_yusyo,
                SUM(yn_syoryaku) AS yn_syoryaku
            FROM public.claim_inf A
            LEFT JOIN public.claim_sonhi B
                ON B.no_claim = A.no_claim
            WHERE SUBSTR(dt_kakunin, 1, 6) >= :dt_start
                AND SUBSTR(dt_kakunin, 1, 6) <= :dt_end
        ";

        if (isset($cd_bumon)) {
            $sql .= " AND cd_bumon = :cd_bumon";
        }

        $sql .= "
            GROUP BY SUBSTR(dt_kakunin, 1, 6)
            ORDER BY SUBSTR(dt_kakunin, 1, 6)
        ";

        $stmt = $this->pdo->prepare($sql);

        $dt_yyyymm = FiscalYear::getNendoyyyymm($kb_nendo);
        $stmt->bindValue(':dt_start', $dt_yyyymm[0] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':dt_end', $dt_yyyymm[5] ?? '', PDO::PARAM_STR);

        if (isset($cd_bumon)) {
            $stmt->bindValue(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = (array)$stmt->fetchAll();

        if (count($result) === 0) {
            return $func_init();
        }

        $aggs_yusyo = array_column($result, 'yn_yusyo', 'dt_yyyymm');
        $aggs_syoryaku = array_column($result, 'yn_syoryaku', 'dt_yyyymm');
        $items = [];

        foreach ($dt_yyyymm as $yyyymm) {
            $item['yn_yusyo'] = isset($aggs_yusyo[$yyyymm]) ?
                $aggs_yusyo[$yyyymm] : 0;
            $item['yn_syoryaku'] = isset($aggs_syoryaku[$yyyymm]) ?
                $aggs_syoryaku[$yyyymm] : 0;
            $items[] = $item;
        }
        return $items;
    }
}
