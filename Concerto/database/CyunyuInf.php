<?php

/**
*   cyunyu_nf
*
*   @version 171016
*/

declare(strict_types=1);

namespace Concerto\database;

use DateTime;
use PDO;
use Concerto\standard\ModelDb;

class CyunyuInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.cyunyu_inf';
    
    /**
    *   no_seq最大値取得
    *
    *   @param string $no_cyu
    *   @param string $no_ko
    *   @return int
    */
    public function getMaxNoSeq($no_cyu, $no_ko)
    {
        /**
        *   プリペア
        *
        *   @var resorce
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
        
        $stmt->bindParam(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_seq'])) ?        0 : $result['no_seq'];
    }
    
    /**
    *   nm_tantoリスト
    *
    *   @param string $nendo_start 開始年度
    *   @param string $nendo_end 終了年度
    *   @return array [[nm_tanto]]
    */
    public function getNmTantoList($nendo_start, $nendo_end)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT DISTINCT nm_tanto 
                    FROM {$this->schema} 
                    WHERE kb_nendo BETWEEN :nendo_start AND :nendo_end
                        AND nm_tanto NOT LIKE '%国内出張%' 
                        AND nm_tanto != '' 
                    ORDER BY nm_tanto 
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':nendo_start', $nendo_start, PDO::PARAM_STR);
        $stmt->bindParam(':nendo_end', $nendo_end, PDO::PARAM_STR);
        $stmt->execute();
        
        return  (array)$stmt->fetchAll();
    }
    
    /**
    *   nm_syohinリスト
    *
    *   @param string $nendo_start 開始年度
    *   @param string $nendo_end 終了年度
    *   @return array [[nm_syohin]]
    */
    public function getNmSyohinList($nendo_start, $nendo_end)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT DISTINCT nm_syohin 
                    FROM {$this->schema} 
                    WHERE kb_nendo BETWEEN :nendo_start AND :nendo_end
                        AND nm_tanto NOT LIKE '%国内出張%' 
                        AND nm_tanto != '' 
                    ORDER BY nm_syohin 
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':nendo_start', $nendo_start, PDO::PARAM_STR);
        $stmt->bindParam(':nendo_end', $nendo_end, PDO::PARAM_STR);
        $stmt->execute();
        
        return  (array)$stmt->fetchAll();
    }
    
    /**
    *   月別集計リスト
    *
    *   @param string $cd_bumon 部門
    *   @param string $start 開始年月yyyymm
    *   @param string $end 終了年月yyyymm
    *   @param string $kb_cyunyu 0:計画/1:実績/2:予測
    *   @param string $kb_cyumon 0:受注/1:A/2:B/3:C
    *   @return array 集計値
    */
    public function getMonAggregate(
        $cd_bumon,
        $start,
        $end,
        $kb_cyunyu,
        $kb_cyumon = null
    ) {
        //計画・実績
        if ($kb_cyunyu != '2') {
            return $this->doGetMonAggregate($cd_bumon, $start, $end, $kb_cyunyu);
        }
        
        //予測
        $plans = $this->doGetMonAggregate($cd_bumon, $start, $end, '0', $kb_cyumon);
        $performances = $this->doGetMonAggregate($cd_bumon, $start, $end, '1', $kb_cyumon);
        
        return array_map(
            function ($plan, $performance) {
                $items['yyyymm'] = $plan['yyyymm'];
                $items['dt_kanjyo'] = $plan['yyyymm'];
                $items['kb_cyunyu'] = '2';
                
                if ($items['yyyymm'] < date('Ym')) {
                    $items['tm_cyokka'] = $performance['tm_cyokka'];
                    $items['yn_cyokka'] = $performance['yn_cyokka'];
                    $items['yn_cyokuzai'] = $performance['yn_cyokuzai'];
                    $items['yn_ryohi'] = $performance['yn_ryohi'];
                    $items['yn_etc'] = $performance['yn_etc'];
                } else {
                    $items['tm_cyokka'] =
                        ($plan['tm_cyokka'] > $performance['tm_cyokka']) ?
                        $plan['tm_cyokka'] : $performance['tm_cyokka'];
                    $items['yn_cyokka'] =
                        ($plan['yn_cyokka'] > $performance['yn_cyokka']) ?
                        $plan['yn_cyokka'] : $performance['yn_cyokka'];
                    $items['yn_cyokuzai'] =
                        ($plan['yn_cyokuzai'] > $performance['yn_cyokuzai']) ?
                        $plan['yn_cyokuzai'] : $performance['yn_cyokuzai'];
                    $items['yn_ryohi'] =
                        ($plan['yn_ryohi'] > $performance['yn_ryohi']) ?
                        $plan['yn_ryohi'] : $performance['yn_ryohi'];
                    $items['yn_etc'] =
                        ($plan['yn_etc'] > $performance['yn_etc']) ?
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
    *   @param string $cd_bumon
    *   @param string $start
    *   @param string $end
    *   @param string $kb_cyunyu
    *   @param string|null $kb_cyumon
    *   @return array
    *   @see getMonAggregate
    **/
    private function doGetMonAggregate(
        $cd_bumon,
        $start,
        $end,
        $kb_cyunyu,
        $kb_cyumon = null
    ) {
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
                    , SUM(yn_ryohi) AS yn_ryohi 
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
        
        $startFull = DateTime::createFromFormat('Ym', $start)
            ->modify('first day of')
            ->modify('midnight')
            ->format('Ymd')
            ;
        $endFull = DateTime::createFromFormat('Ym', $end)
            ->modify('last day of')
            ->modify('midnight')
            ->format('Ymd')
            ;
        $bumon = "{$cd_bumon}%";
        $cyunyu = $kb_cyunyu;
        $cyumon = "{$kb_cyumon}%";
        
        $stmt->bindParam(':start_f', $startFull, PDO::PARAM_STR);
        $stmt->bindParam(':end_f', $endFull, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_STR);
        $stmt->bindParam(':end', $end, PDO::PARAM_STR);
        $stmt->bindParam(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->bindParam(':cyunyu', $cyunyu, PDO::PARAM_STR);
        $stmt->bindParam(':cyumon', $cyumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
    
    /**
    *   月別集計リスト
    *
    *   @param string $no_cyu 注番
    *   @param string $no_ko 項番
    *   @param string $kb_cyunyu 0:計画/1:実績/2:予測
    *   @return array 集計値
    */
    public function getCyubanMonAggregate($no_cyu, $no_ko, $kb_cyunyu)
    {
        if ($kb_cyunyu == '2') {
            $sql = "
                SELECT dt_kanjyo 
                    , (A.tm_Ocyokka + A.tm_Fcyokka + (CASE WHEN ABS(A.tm_Acyokka) >= ABS(A.tm_Bcyokka) then A.tm_Acyokka ELSE A.tm_Bcyokka END)) AS tm_cyokka 
                    , (A.yn_Ocyokka + A.yn_Fcyokka + (CASE WHEN ABS(A.yn_Acyokka) >= ABS(A.yn_Bcyokka) then A.yn_Acyokka ELSE A.yn_Bcyokka END)) AS yn_cyokka 
                    
                    , (A.yn_Ocyokuzai + (CASE WHEN ABS(A.yn_Acyokuzai) >= ABS(A.yn_Bcyokuzai) then A.yn_Acyokuzai ELSE A.yn_Bcyokuzai END) 
                        + (CASE WHEN ABS(A.yn_Fcyokuzai) >= ABS(A.yn_Ccyokuzai) then A.yn_Fcyokuzai ELSE A.yn_Ccyokuzai END)) AS yn_cyokuzai 
                    
                    , (A.yn_Oryohi + A.yn_Fryohi + (CASE WHEN ABS(A.yn_Aryohi) >= ABS(A.yn_Bryohi) then A.yn_Aryohi ELSE A.yn_Bryohi END)) AS yn_ryohi 
                    , (A.yn_Oetc + A.yn_Fetc + (CASE WHEN ABS(A.yn_Aetc) >= ABS(A.yn_Betc) then A.yn_Aetc ELSE A.yn_Betc END)) AS yn_etc 
                FROM
                    (SELECT dt_kanjyo 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN tm_cyokka END), 0) AS tm_Ocyokka 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN tm_cyokka END), 0) AS tm_Fcyokka 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN tm_cyokka END), 0) AS tm_Acyokka 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN tm_cyokka END), 0) AS tm_Bcyokka 
                        
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN yn_cyokka END), 0) AS yn_Ocyokka 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN yn_cyokka END), 0) AS yn_Fcyokka 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_cyokka END), 0) AS yn_Acyokka 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_cyokka END), 0) AS yn_Bcyokka 
                        
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN yn_cyokuzai END), 0) AS yn_Ocyokuzai 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN yn_cyokuzai END), 0) AS yn_Fcyokuzai 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_cyokuzai END), 0) AS yn_Acyokuzai 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_cyokuzai END), 0) AS yn_Bcyokuzai 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  > TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_cyokuzai END), 0) AS yn_Ccyokuzai 
                        
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN yn_ryohi END), 0) AS yn_Oryohi 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN yn_ryohi END), 0) AS yn_Fryohi 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_ryohi END), 0) AS yn_Aryohi 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_ryohi END), 0) AS yn_Bryohi 
                        
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN yn_etc END), 0) AS yn_Oetc 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN yn_etc END), 0) AS yn_Fetc 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '1' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_etc END), 0) AS yn_Aetc 
                        , COALESCE(SUM (CASE WHEN kb_cyunyu = '0' AND dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN yn_etc END), 0) AS yn_Betc 
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
                    , SUM(yn_ryohi) AS yn_ryohi 
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
        
        $stmt->bindParam(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':koban', $koban, PDO::PARAM_STR);
        
        if ($kb_cyunyu != '2') {
            $stmt->bindParam(':cyunyu', $cyunyu, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
    
    /**
    *   年度リスト
    *
    *   @return array [[kb_nendo]]
    */
    public function getNendoList()
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
