<?php

/**
*   cyunyu_nf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use DateTimeImmutable;
use InvalidArgumentException;
use PDO;
use Concerto\standard\ModelDb;

class CyunyuInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.cyunyu_inf';

    /**
    *   no_seq最大値取得
    *
    *   @param string $no_cyu
    *   @param string $no_ko
    *   @return int
    */
    public function getMaxNoSeq(
        string $no_cyu,
        string $no_ko
    ): int {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_seq) AS no_seq 
                    FROM {$this->schema} 
                    WHERE no_cyu = :no_cyu 
                    AND no_ko = :no_ko 
                    AND kb_cyunyu = '0'
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchColumn();
        return empty($result) ? 0 : (int)$result;
    }

    /**
    *   月別集計リスト
    *
    *   @param ?string $cd_bumon
    *   @param string $start
    *   @param string $end
    *   @param string $kb_cyunyu
    *   @param ?string $kb_cyumon
    *   @return mixed[]
    */
    public function getMonAggregate(
        ?string $cd_bumon,
        string $start,
        string $end,
        string $kb_cyunyu,
        ?string $kb_cyumon = null
    ): array {
        //計画・実績
        if ($kb_cyunyu !== '2') {
            return $this->doGetMonAggregate(
                $cd_bumon,
                $start,
                $end,
                $kb_cyunyu
            );
        }

        //予測
        $plans = $this->doGetMonAggregate(
            $cd_bumon,
            $start,
            $end,
            '0',
            $kb_cyumon
        );
        $performances = $this->doGetMonAggregate(
            $cd_bumon,
            $start,
            $end,
            '1',
            $kb_cyumon
        );

        return array_map(
            function ($plan, $performance) {
                $items['yyyymm'] = $plan['yyyymm'];
                $items['dt_kanjyo'] = $plan['yyyymm'];
                $items['kb_cyunyu'] = '2';

                if ($items['yyyymm'] < date('Ym')) {
                    $items['tm_cyokka'] =
                        $performance['tm_cyokka'];
                    $items['yn_cyokka'] =
                        $performance['yn_cyokka'];
                    $items['yn_cyokuzai'] =
                        $performance['yn_cyokuzai'];
                    $items['yn_etc'] =
                        $performance['yn_etc'];
                } else {
                    $items['tm_cyokka'] =
                        $plan['tm_cyokka'] > $performance['tm_cyokka'] ?
                        $plan['tm_cyokka'] : $performance['tm_cyokka'];
                    $items['yn_cyokka'] =
                        $plan['yn_cyokka'] > $performance['yn_cyokka'] ?
                        $plan['yn_cyokka'] : $performance['yn_cyokka'];
                    $items['yn_cyokuzai'] =
                        $plan['yn_cyokuzai'] > $performance['yn_cyokuzai'] ?
                        $plan['yn_cyokuzai'] : $performance['yn_cyokuzai'];
                    $items['yn_etc'] =
                        $plan['yn_etc'] > $performance['yn_etc'] ?
                        $plan['yn_etc'] : $performance['yn_etc'];
                }
                return $items;
            },
            $plans,
            $performances
        );
    }

    /**
    *   doGetMonAggregate
    *
    *   @param ?string $cd_bumon
    *   @param string $start
    *   @param string $end
    *   @param string $kb_cyunyu
    *   @param ?string $kb_cyumon
    *   @return mixed[]
    *   @see getMonAggregate
    */
    private function doGetMonAggregate(
        ?string $cd_bumon,
        string $start,
        string $end,
        string $kb_cyunyu,
        ?string $kb_cyumon = null
    ): array {
        $sql = "
            WITH RECURSIVE period(dt, cnt) AS (
                SELECT CAST (:start_f AS timestamp)
                    ,1
                UNION
                    SELECT dt + CAST ('1 month' AS interval)
                        ,cnt + 1
                    FROM period
                    WHERE CAST(dt + CAST ('1 month' AS interval) AS date)
                        <= CAST(:end_f AS date)
                    AND cnt < 240
            ), yyyymms(dt) AS (
                SELECT TO_CHAR(dt, 'yyyymm') AS dt
                FROM period
            )
            SELECT A.dt AS yyyymm
                , B.*
            FROM yyyymms A
            LEFT JOIN
                (SELECT dt_kanjyo, kb_cyunyu 
                    , SUM(tm_cyokka) AS tm_cyokka 
                    , SUM(yn_cyokka) AS yn_cyokka 
                    , SUM(yn_cyokuzai) AS yn_cyokuzai
                    , SUM(yn_etc) AS yn_etc 
                FROM public.cyunyu_inf B1
                JOIN 
                    (SELECT DISTINCT no_cyu
                    FROM public.cyuban_inf
                    WHERE kb_cyumon LIKE :cyumon
                    ) B2
                    ON B2.no_cyu = B1.no_cyu
                WHERE dt_kanjyo >= :start
                    AND dt_kanjyo <= :end 
                    AND cd_bumon LIKE :bumon
                    AND kb_cyunyu = :cyunyu
                GROUP BY kb_cyunyu, dt_kanjyo 
                ) B
                ON B.dt_kanjyo = A.dt
            ORDER BY B.kb_cyunyu, A.dt
        ";
        $stmt = $this->pdo->prepare($sql);

        $startFull = DateTimeImmutable::createFromFormat(
            '!Ym',
            $start
        );

        if ($startFull === false) {
            throw new InvalidArgumentException(
                "invalid start date:{$start}"
            );
        }

        $startFull = $startFull->format('Ymd');

        $endFull = DateTimeImmutable::createFromFormat(
            '!Ym',
            $end
        );

        if ($endFull === false) {
            throw new InvalidArgumentException(
                "invalid start date:{$end}"
            );
        }

        $endFull = $endFull->format('Ymd');

        $bumon = "{$cd_bumon}%";
        $cyunyu = $kb_cyunyu;
        $cyumon = "{$kb_cyumon}%";

        $stmt->bindValue(':start_f', $startFull, PDO::PARAM_STR);
        $stmt->bindValue(':end_f', $endFull, PDO::PARAM_STR);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $end, PDO::PARAM_STR);
        $stmt->bindValue(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->bindValue(':cyunyu', $cyunyu, PDO::PARAM_STR);
        $stmt->bindValue(':cyumon', $cyumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   月別集計リスト
    *
    *   @param string $no_cyu
    *   @param ?string $no_ko
    *   @param ?string $kb_cyunyu
    *   @return mixed[]
    */
    public function getCyubanMonAggregate(
        string $no_cyu,
        ?string $no_ko,
        ?string $kb_cyunyu
    ): array {
        if ($kb_cyunyu === '2') {
            $sql = "
                SELECT dt_kanjyo 
                    , (A.tm_Ocyokka + A.tm_Fcyokka +
                        (CASE WHEN ABS(A.tm_Acyokka) >= ABS(A.tm_Bcyokka)
                            THEN A.tm_Acyokka
                            ELSE A.tm_Bcyokka
                            END)) AS tm_cyokka 
                    , (A.yn_Ocyokka + A.yn_Fcyokka +
                        (CASE WHEN ABS(A.yn_Acyokka) >= ABS(A.yn_Bcyokka)
                            THEN A.yn_Acyokka
                            ELSE A.yn_Bcyokka
                            END)) AS yn_cyokka 

                    , (A.yn_Ocyokuzai +
                        (CASE WHEN ABS(A.yn_Acyokuzai) >= ABS(A.yn_Bcyokuzai)
                            THEN A.yn_Acyokuzai
                            ELSE A.yn_Bcyokuzai
                            END) 
                        + (CASE WHEN ABS(A.yn_Fcyokuzai) >= ABS(A.yn_Ccyokuzai)
                            THEN A.yn_Fcyokuzai
                            ELSE A.yn_Ccyokuzai
                            END)) AS yn_cyokuzai 

                    , (A.yn_Oetc + A.yn_Fetc +
                        (CASE WHEN ABS(A.yn_Aetc) >= ABS(A.yn_Betc)
                            THEN A.yn_Aetc
                            ELSE A.yn_Betc
                            END)) AS yn_etc 

                FROM
                    (SELECT dt_kanjyo 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <=
                                TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm')
                            THEN tm_cyokka END), 0) AS tm_Ocyokka 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >=
                                TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm')
                            THEN tm_cyokka END), 0) AS tm_Fcyokka 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN tm_cyokka END), 0) AS tm_Acyokka 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN tm_cyokka END), 0) AS tm_Bcyokka 

                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= 
                                TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') 
                            THEN yn_cyokka END), 0) AS yn_Ocyokka 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= 
                                TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') 
                            THEN yn_cyokka END), 0) AS yn_Fcyokka 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN yn_cyokka END), 0) AS yn_Acyokka 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN yn_cyokka END), 0) AS yn_Bcyokka 

                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= 
                                TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') 
                            THEN yn_cyokuzai END), 0) AS yn_Ocyokuzai 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= 
                                TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') 
                            THEN yn_cyokuzai END), 0) AS yn_Fcyokuzai 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN yn_cyokuzai END), 0) AS yn_Acyokuzai 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN yn_cyokuzai END), 0) AS yn_Bcyokuzai 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  > 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN yn_cyokuzai END), 0) AS yn_Ccyokuzai 

                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= 
                                TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') 
                            THEN yn_etc END), 0) AS yn_Oetc 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= 
                                TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') 
                            THEN yn_etc END), 0) AS yn_Fetc 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') 
                            THEN yn_etc END), 0) AS yn_Aetc 
                        , COALESCE(SUM (
                            CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = 
                                TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                            THEN yn_etc END), 0) AS yn_Betc

                    FROM public.cyunyu_inf 
                    WHERE no_cyu = :cyuban 
                        AND no_ko LIKE :koban 
                    GROUP BY  dt_kanjyo
                    ) A
                ORDER BY dt_kanjyo
            ";
        } else {
            $sql = "
                SELECT dt_kanjyo, kb_cyunyu 
                    , SUM(tm_cyokka) AS tm_cyokka 
                    , SUM(yn_cyokka) AS yn_cyokka 
                    , SUM(yn_cyokuzai) AS yn_cyokuzai
                    , SUM(yn_etc) AS yn_etc 
                FROM public.cyunyu_inf 
                WHERE no_cyu = :cyuban 
                    AND no_ko LIKE :koban 
                    AND kb_cyunyu LIKE :cyunyu
                GROUP BY kb_cyunyu, dt_kanjyo 
                ORDER BY kb_cyunyu, dt_kanjyo
            ";
        }

        $stmt = $this->pdo->prepare($sql);

        $koban = $no_ko . '%';
        $cyunyu = $kb_cyunyu . '%';

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':koban', $koban, PDO::PARAM_STR);

        if ($kb_cyunyu !== '2') {
            $stmt->bindValue(':cyunyu', $cyunyu, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   年度リスト
    *
    *   @return mixed[]
    */
    public function getNendoList(): array
    {
        $sql = "
            SELECT DISTINCT kb_nendo 
            FROM {$this->schema} 
            WHERE kb_nendo != ''
            ORDER BY kb_nendo DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
