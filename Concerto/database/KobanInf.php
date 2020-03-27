<?php

/**
*   koban_inf
*
*   @version 160901
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class KobanInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.koban_inf';
    
    /**
    *   集計実行
    *
    *   @param string|null $no_cyu 注番
    *   @param string|null $no_ko 項番
    */
    public function aggregate($no_cyu = null, $no_ko = null)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                UPDATE {$this->name} Z SET 
                    tm_pcyokka = Y.tm_pcyokka, 
                    yn_pcyokka = Y.yn_pcyokka, 
                    yn_pcyokuzai = Y.yn_pcyokuzai, 
                    yn_petc = Y.yn_petc, 
                    yn_pryohi = Y.yn_pryohi, 
                    tm_rcyokka = Y.tm_rcyokka, 
                    yn_rcyokka = Y.yn_rcyokka, 
                    yn_rcyokuzai = Y.yn_rcyokuzai, 
                    yn_retc = Y.yn_retc, 
                    yn_rryohi = Y.yn_rryohi, 
                    tm_ycyokka = Y.tm_ycyokka, 
                    yn_ycyokka = Y.yn_ycyokka, 
                    yn_ycyokuzai = Y.yn_ycyokuzai, 
                    yn_yetc = Y.yn_yetc, 
                    yn_yryohi = Y.yn_yryohi, 
                    kb_keikaku = CASE (Y.yn_pcyokka + Y.yn_pcyokuzai + Y.yn_petc) WHEN 0 THEN '0' ELSE '1' END 
                FROM 
                    (SELECT 
                        FF.no_cyu,
                        FF.no_ko, 
                        FF.tm_pcyokka, FF.yn_pcyokka, FF.yn_pcyokuzai, FF.yn_petc, FF.yn_pryohi, 
                        FF.tm_rcyokka, FF.yn_rcyokka, FF.yn_rcyokuzai, FF.yn_retc, FF.yn_rryohi, 
                        COALESCE(FF.tm_ycyokka, 0) AS tm_ycyokka, 
                        COALESCE(FF.yn_ycyokka, 0) AS yn_ycyokka,
                        COALESCE(FF.yn_ycyokuzai, 0) AS yn_ycyokuzai, 
                        COALESCE(FF.yn_yetc, 0) AS yn_yetc, 
                        COALESCE(FF.yn_yryohi, 0) AS yn_yryohi 
                    FROM 
                        (SELECT C.no_cyu, C.no_ko, 
                            COALESCE(DD.tm_cyokka,0) AS tm_pcyokka, 
                            COALESCE(DD.yn_cyokka,0) AS yn_pcyokka, 
                            COALESCE(DD.yn_cyokuzai,0) AS yn_pcyokuzai, 
                            COALESCE(DD.yn_etc,0) AS yn_petc, 
                            COALESCE(DD.yn_ryohi,0) AS yn_pryohi, 
                            COALESCE(BB.tm_cyokka,0) AS tm_rcyokka, 
                            COALESCE(BB.yn_cyokka,0) AS yn_rcyokka, 
                            COALESCE(BB.yn_cyokuzai,0) AS yn_rcyokuzai, 
                            COALESCE(BB.yn_etc,0) AS yn_retc, 
                            COALESCE(BB.yn_ryohi,0) AS yn_rryohi, 
                            
                            (EE.tm_Ocyokka + EE.tm_Fcyokka + (CASE WHEN ABS(EE.tm_Acyokka) >= ABS(EE.tm_Bcyokka) then EE.tm_Acyokka ELSE EE.tm_Bcyokka END)) AS tm_ycyokka, 
                            (EE.yn_Ocyokka + EE.yn_Fcyokka + (CASE WHEN ABS(EE.yn_Acyokka) >= ABS(EE.yn_Bcyokka) then EE.yn_Acyokka ELSE EE.yn_Bcyokka END)) AS yn_ycyokka, 
                            
                            (EE.yn_Ocyokuzai + (CASE WHEN ABS(EE.yn_Acyokuzai) >= ABS(EE.yn_Bcyokuzai) then EE.yn_Acyokuzai ELSE EE.yn_Bcyokuzai END) 
                                + (CASE WHEN ABS(EE.yn_Fcyokuzai) >= ABS(EE.yn_Ccyokuzai) then EE.yn_Fcyokuzai ELSE EE.yn_Ccyokuzai END)) AS yn_ycyokuzai, 
                            
                            (EE.yn_Oryohi + EE.yn_Fryohi + (CASE WHEN ABS(EE.yn_Aryohi) >= ABS(EE.yn_Bryohi) then EE.yn_Aryohi ELSE EE.yn_Bryohi END)) AS yn_yryohi, 
                            (EE.yn_Oetc + EE.yn_Fetc + (CASE WHEN ABS(EE.yn_Aetc) >= ABS(EE.yn_Betc) then EE.yn_Aetc ELSE EE.yn_Betc END)) AS yn_yetc 
                            
                        FROM  {$this->schema} C 
                        LEFT JOIN 
                            (SELECT B.no_cyu, B.no_ko, B.kb_cyunyu, SUM(B.tm_cyokka) AS tm_cyokka, SUM(B.yn_cyokka) AS yn_cyokka, 
                                SUM(B.yn_cyokuzai) AS yn_cyokuzai, SUM(B.yn_etc) AS yn_etc, SUM(B.yn_ryohi) AS yn_ryohi 
                            FROM public.cyunyu_inf B 
                            GROUP BY B.no_cyu, B.no_ko, B.kb_cyunyu
                            ) BB 
                            ON BB.no_cyu = C.no_cyu AND BB.no_ko = C.no_ko AND BB.kb_cyunyu = '1' 
                        LEFT JOIN 
                            (SELECT D.no_cyu, D.no_ko, D.kb_cyunyu, SUM(D.tm_cyokka) AS tm_cyokka, SUM(D.yn_cyokka) AS yn_cyokka, 
                                SUM(D.yn_cyokuzai) AS yn_cyokuzai, SUM(D.yn_etc) AS yn_etc, SUM(D.yn_ryohi) AS yn_ryohi 
                            FROM public.cyunyu_inf D 
                            GROUP BY D.no_cyu, D.no_ko, D.kb_cyunyu 
                            ) DD 
                            ON DD.no_cyu = C.no_cyu AND DD.no_ko = C.no_ko AND DD.kb_cyunyu = '0' 
                        LEFT JOIN 
                            (SELECT E.no_cyu, E.no_ko, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN E.tm_cyokka END), 0) AS tm_Ocyokka, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN E.tm_cyokka END), 0) AS tm_Fcyokka, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.tm_cyokka END), 0) AS tm_Acyokka, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.tm_cyokka END), 0) AS tm_Bcyokka, 
                                
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN E.yn_cyokka END), 0) AS yn_Ocyokka, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN E.yn_cyokka END), 0) AS yn_Fcyokka, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_cyokka END), 0) AS yn_Acyokka, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_cyokka END), 0) AS yn_Bcyokka, 
                                
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN E.yn_cyokuzai END), 0) AS yn_Ocyokuzai, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN E.yn_cyokuzai END), 0) AS yn_Fcyokuzai, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_cyokuzai END), 0) AS yn_Acyokuzai, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_cyokuzai END), 0) AS yn_Bcyokuzai, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  > TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_cyokuzai END), 0) AS yn_Ccyokuzai, 
                                
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN E.yn_ryohi END), 0) AS yn_Oryohi, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN E.yn_ryohi END), 0) AS yn_Fryohi, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_ryohi END), 0) AS yn_Aryohi, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_ryohi END), 0) AS yn_Bryohi, 
                                
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <= TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm') THEN E.yn_etc END), 0) AS yn_Oetc, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >= TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm') THEN E.yn_etc END), 0) AS yn_Fetc, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_etc END), 0) AS yn_Aetc, 
                                COALESCE(SUM (CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  = TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm') THEN E.yn_etc END), 0) AS yn_Betc  
                                
                                FROM public.cyunyu_inf E 
                                GROUP BY E.no_cyu, E.no_ko 
                            ) EE
                            ON EE.no_cyu = C.no_cyu AND EE.no_ko = C.no_ko 
                        WHERE (
                                C.no_cyu = :no_cyu 
                                AND C.no_ko = :no_ko 
                            ) IS NOT FALSE 
                        ) FF 
                    ) Y
                WHERE (Z.no_cyu = :no_cyu 
                    AND Z.no_ko = :no_ko 
                    ) IS NOT FALSE 
                    AND Y.no_cyu = Z.no_cyu AND Y.no_ko = Z.no_ko
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();
    }
    
    /**
    *   集計リスト
    *
    *   @param string|null $kb_nendo 年度
    *   @param string|null $cd_bumon 部門
    *   @param string|null $kb_cyumon 注文確度
    *   @param string|null $no_cyu 注番
    *   @param string|null $no_ko 項番
    *   @return array 集計値
    */
    public function getAggregate(
        $kb_nendo = null,
        $cd_bumon = null,
        $kb_cyumon = null,
        $no_cyu = null,
        $no_ko = null
    ) {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT SUM(yn_tov) AS yn_tov 
                        , SUM(tm_pcyokka) AS tm_pcyokka 
                        , SUM(yn_pcyokka) AS yn_pcyokka 
                        , SUM(yn_pcyokuzai) AS yn_pcyokuzai 
                        , SUM(yn_pryohi) AS yn_pryohi 
                        , SUM(yn_petc) AS yn_petc 
                        , SUM(tm_ycyokka) AS tm_ycyokka 
                        , SUM(yn_ycyokka) AS yn_ycyokka 
                        , SUM(yn_ycyokuzai) AS yn_ycyokuzai 
                        , SUM(yn_yryohi) AS yn_yryohi 
                        , SUM(yn_yetc) AS yn_yetc 
                        , SUM(tm_rcyokka) AS tm_rcyokka 
                        , SUM(yn_rcyokka) AS yn_rcyokka 
                        , SUM(yn_rcyokuzai) AS yn_rcyokuzai 
                        , SUM(yn_rryohi) AS yn_rryohi 
                        , SUM(yn_retc) AS yn_retc 
                    FROM {$this->schema} 
                    WHERE (kb_nendo = :kb_nendo 
                        AND cd_bumon = :cd_bumon 
                        AND kb_cyumon = :kb_cyumon 
                        AND no_cyu = :no_cyu 
                        AND no_ko = :no_ko 
                        ) IS NOT FALSE 
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->bindParam(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->bindParam(':kb_cyumon', $kb_cyumon, PDO::PARAM_STR);
        $stmt->bindParam(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetchAll();
        
        if (count($result) == 0) {
            return array();
        } else {
            $aggregate = array();
            
            foreach ($result as $list) {
                $ar = $this->calAggrigateData($list);
                $aggregate[] = array_merge($list, $ar);
            }
        }
        return $aggregate;
    }
    
    /**
    *   月別集計リスト
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門
    *   @return array 集計値
    */
    public function getMonAggregate($kb_nendo, $cd_bumon)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                SELECT dt_pkansei_m AS dt_yyyymm
                    , SUM(yn_tov) AS yn_tov
                    , SUM(tm_pcyokka) AS tm_pcyokka
                    , SUM(yn_pcyokka) AS yn_pcyokka
                    , SUM(yn_pcyokuzai) AS yn_pcyokuzai
                    , SUM(yn_pryohi) AS yn_pryohi
                    , SUM(yn_petc) AS yn_petc
                    
                    , SUM(tm_ycyokka) AS tm_ycyokka
                    , SUM(yn_ycyokka) AS yn_ycyokka
                    , SUM(yn_ycyokuzai) AS yn_ycyokuzai
                    , SUM(yn_yryohi) AS yn_yryohi
                    , SUM(yn_yetc) AS yn_yetc
                    
                    , SUM(tm_rcyokka) AS tm_rcyokka
                    , SUM(yn_rcyokka) AS yn_rcyokka
                    , SUM(yn_rcyokuzai) AS yn_rcyokuzai
                    , SUM(yn_rryohi) AS yn_rryohi
                    , SUM(yn_retc) AS yn_retc
                FROM public.koban_inf 
                WHERE cd_bumon LIKE :bumon
                    AND kb_nendo LIKE :nendo
                GROUP BY dt_pkansei_m
                ORDER BY dt_pkansei_m
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $bumon = $cd_bumon . '%';
        $nendo = $kb_nendo . '%';
        
        $stmt->bindParam(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->bindParam(':nendo', $nendo, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if (count($result) == 0) {
            return array();
        } else {
            $aggregate = array();
            
            foreach ($result as $list) {
                $ar = $this->calAggrigateData($list);
                $aggregate[] = array_merge($list, $ar);
            }
        }
        return $aggregate;
    }
    
    protected function calAggrigateData(array $dataset)
    {
        $yn_pcyunyu = (string)($dataset['yn_pcyokka']
            + $dataset['yn_pcyokuzai']
            + $dataset['yn_pryohi']
            + $dataset['yn_petc']
        );
        $yn_psoneki = (string)($dataset['yn_tov'] - $yn_pcyunyu);
        
        $ri_psoneki = (empty($dataset['yn_tov'])) ?
            '0.0' : sprintf('%4.1f', round(($yn_psoneki / $dataset['yn_tov']) * 100, 1));
        
        $yn_ycyunyu = (string)($dataset['yn_ycyokka']
            + $dataset['yn_ycyokuzai']
            + $dataset['yn_yryohi']
            + $dataset['yn_yetc']
        );
        $yn_ysoneki = (string)($dataset['yn_tov'] - $yn_ycyunyu);
        
        $ri_ysoneki = (empty($dataset['yn_tov'])) ?
            '0.0' : sprintf('%4.1f', round(($yn_ysoneki / $dataset['yn_tov']) * 100, 1));
        
        $yn_rcyunyu = (string)($dataset['yn_rcyokka']
            + $dataset['yn_rcyokuzai']
            + $dataset['yn_rryohi']
            + $dataset['yn_retc']
        );
        
        $yn_rsoneki = (string)($dataset['yn_tov'] - $yn_rcyunyu);
        
        $ri_rsoneki = (empty($dataset['yn_tov'])) ?
            '0.0' : sprintf('%4.1f', round(($yn_rsoneki / $dataset['yn_tov']) * 100, 1));
        
        return array ('yn_pcyunyu' => $yn_pcyunyu
            , 'yn_psoneki' => $yn_psoneki
            , 'ri_psoneki' => $ri_psoneki
            , 'yn_ycyunyu' => $yn_ycyunyu
            , 'yn_ysoneki' => $yn_ysoneki
            , 'ri_ysoneki' => $ri_ysoneki
            , 'yn_rcyunyu' => $yn_rcyunyu
            , 'yn_rsoneki' => $yn_rsoneki
            , 'ri_rsoneki' => $ri_rsoneki
        );
    }
}
