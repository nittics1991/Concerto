<?php

/**
*   koban_inf
*
*   @version 230329
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class KobanInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.koban_inf';

    /**
    *   集計実行
    *
    *   @param ?string $no_cyu 注番
    *   @param ?string $no_ko 項番
    *   @return void
    */
    public function aggregate(
        ?string $no_cyu = null,
        ?string $no_ko = null
    ): void {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                WITH cyunyus AS (
                    SELECT *
                    FROM public.cyunyu_inf
                    WHERE (
                        no_cyu = :no_cyu
                        AND no_ko = :no_ko
                        ) IS NOT FALSE
                )
                UPDATE {$this->name} Z SET
                    tm_pcyokka = Y.tm_pcyokka,
                    yn_pcyokka = Y.yn_pcyokka,
                    yn_pcyokuzai = Y.yn_pcyokuzai,
                    yn_petc = Y.yn_petc,
                    tm_rcyokka = Y.tm_rcyokka,
                    yn_rcyokka = Y.yn_rcyokka,
                    yn_rcyokuzai = Y.yn_rcyokuzai,
                    yn_retc = Y.yn_retc,
                    tm_ycyokka = Y.tm_ycyokka,
                    yn_ycyokka = Y.yn_ycyokka,
                    yn_ycyokuzai = Y.yn_ycyokuzai,
                    yn_yetc = Y.yn_yetc,
                    kb_keikaku =
                        CASE (Y.yn_pcyokka + Y.yn_pcyokuzai + Y.yn_petc)
                            WHEN 0 THEN '0'
                            ELSE '1'
                            END
                FROM
                    (SELECT
                        FF.no_cyu,
                        FF.no_ko,
                        FF.tm_pcyokka, FF.yn_pcyokka,
                        FF.yn_pcyokuzai, FF.yn_petc,
                        FF.tm_rcyokka, FF.yn_rcyokka,
                        FF.yn_rcyokuzai, FF.yn_retc,
                        COALESCE(FF.tm_ycyokka, 0) AS tm_ycyokka,
                        COALESCE(FF.yn_ycyokka, 0) AS yn_ycyokka,
                        COALESCE(FF.yn_ycyokuzai, 0) AS yn_ycyokuzai,
                        COALESCE(FF.yn_yetc, 0) AS yn_yetc
                    FROM
                        (SELECT C.no_cyu, C.no_ko,
                            COALESCE(DD.tm_cyokka,0) AS tm_pcyokka,
                            COALESCE(DD.yn_cyokka,0) AS yn_pcyokka,
                            COALESCE(DD.yn_cyokuzai,0) AS yn_pcyokuzai,
                            COALESCE(DD.yn_etc,0) AS yn_petc,
                            COALESCE(BB.tm_cyokka,0) AS tm_rcyokka,
                            COALESCE(BB.yn_cyokka,0) AS yn_rcyokka,
                            COALESCE(BB.yn_cyokuzai,0) AS yn_rcyokuzai,
                            COALESCE(BB.yn_etc,0) AS yn_retc,

                            (EE.tm_Ocyokka + EE.tm_Fcyokka
                                + (CASE WHEN ABS(EE.tm_Acyokka) >=
                                    ABS(EE.tm_Bcyokka) THEN EE.tm_Acyokka
                                ELSE EE.tm_Bcyokka 
                                END)) AS tm_ycyokka,
                            (EE.yn_Ocyokka + EE.yn_Fcyokka
                                + (CASE WHEN ABS(EE.yn_Acyokka) >=
                                    ABS(EE.yn_Bcyokka) THEN EE.yn_Acyokka
                                ELSE EE.yn_Bcyokka
                                END)) AS yn_ycyokka,

                            (EE.yn_Ocyokuzai
                                + (CASE WHEN ABS(EE.yn_Acyokuzai) >=
                                    ABS(EE.yn_Bcyokuzai) THEN EE.yn_Acyokuzai
                                ELSE EE.yn_Bcyokuzai
                                END)
                                + (CASE WHEN ABS(EE.yn_Fcyokuzai) >=
                                        ABS(EE.yn_Ccyokuzai) THEN EE.yn_Fcyokuzai
                                    ELSE EE.yn_Ccyokuzai
                                    END)) AS yn_ycyokuzai,

                            (EE.yn_Oetc + EE.yn_Fetc
                                + (CASE WHEN ABS(EE.yn_Aetc) >=
                                    ABS(EE.yn_Betc) THEN EE.yn_Aetc
                                ELSE EE.yn_Betc
                                END)) AS yn_yetc
                        FROM  (
                            SELECT no_cyu, no_ko
                            FROM public.koban_inf
                            WHERE (
                                no_cyu = :no_cyu
                                AND no_ko = :no_ko
                                ) IS NOT FALSE
                            ) C
                        
                        LEFT JOIN
                            (SELECT B.no_cyu, B.no_ko, B.kb_cyunyu,
                                SUM(B.tm_cyokka) AS tm_cyokka,
                                SUM(B.yn_cyokka) AS yn_cyokka,
                                SUM(B.yn_cyokuzai) AS yn_cyokuzai,
                                SUM(B.yn_etc) AS yn_etc
                            FROM cyunyus B
                            GROUP BY B.no_cyu, B.no_ko, B.kb_cyunyu
                            ) BB
                            ON BB.no_cyu = C.no_cyu
                                AND BB.no_ko = C.no_ko
                                AND BB.kb_cyunyu = '1'
                        LEFT JOIN
                            (SELECT D.no_cyu, D.no_ko, D.kb_cyunyu,
                                SUM(D.tm_cyokka) AS tm_cyokka,
                                SUM(D.yn_cyokka) AS yn_cyokka,
                                SUM(D.yn_cyokuzai) AS yn_cyokuzai,
                                SUM(D.yn_etc) AS yn_etc
                            FROM cyunyus D
                            GROUP BY D.no_cyu, D.no_ko, D.kb_cyunyu
                            ) DD
                            ON DD.no_cyu = C.no_cyu
                                AND DD.no_ko = C.no_ko
                                AND DD.kb_cyunyu = '0'
                        LEFT JOIN
                            (SELECT E.no_cyu, E.no_ko,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <=
                                            TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm')
                                        THEN E.tm_cyokka END
                                    ), 0) AS tm_Ocyokka,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >=
                                            TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm')
                                        THEN E.tm_cyokka END
                                    ), 0) AS tm_Fcyokka,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.tm_cyokka END
                                    ), 0) AS tm_Acyokka,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.tm_cyokka END
                                    ), 0) AS tm_Bcyokka,

                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <=
                                            TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokka END
                                    ), 0) AS yn_Ocyokka,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >=
                                            TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokka END
                                    ), 0) AS yn_Fcyokka,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokka END
                                    ), 0) AS yn_Acyokka,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokka END
                                    ), 0) AS yn_Bcyokka,

                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <=
                                            TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokuzai END
                                    ), 0) AS yn_Ocyokuzai,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >=
                                            TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokuzai END
                                    ), 0) AS yn_Fcyokuzai,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokuzai END
                                    ), 0) AS yn_Acyokuzai,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokuzai END
                                    ), 0) AS yn_Bcyokuzai,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  >
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_cyokuzai END
                                    ), 0) AS yn_Ccyokuzai,

                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo <=
                                            TO_CHAR(CURRENT_TIMESTAMP + '-1 MONTH' , 'yyyymm')
                                        THEN E.yn_etc END
                                    ), 0) AS yn_Oetc,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo >=
                                            TO_CHAR(CURRENT_TIMESTAMP + '+1 MONTH' , 'yyyymm')
                                        THEN E.yn_etc END
                                    ), 0) AS yn_Fetc,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '1' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_etc END
                                    ), 0) AS yn_Aetc,
                                COALESCE(
                                    SUM (
                                        CASE WHEN E.kb_cyunyu = '0' AND E.dt_kanjyo  =
                                            TO_CHAR(CURRENT_TIMESTAMP + '+0 MONTH' , 'yyyymm')
                                        THEN E.yn_etc END
                                    ), 0) AS yn_Betc

                                FROM cyunyus E
                                GROUP BY E.no_cyu, E.no_ko
                            ) EE
                            ON EE.no_cyu = C.no_cyu AND EE.no_ko = C.no_ko
                        ) FF
                    ) Y
                WHERE (Z.no_cyu = :no_cyu
                    AND Z.no_ko = :no_ko
                    ) IS NOT FALSE
                    AND Y.no_cyu = Z.no_cyu AND Y.no_ko = Z.no_ko
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
    *   集計リスト
    *
    *   @param ?string $kb_nendo 年度
    *   @param ?string $cd_bumon 部門
    *   @param ?string $kb_cyumon 注文確度
    *   @param ?string $no_cyu 注番
    *   @param ?string $no_ko 項番
    *   @return mixed[] 集計値
    */
    public function getAggregate(
        ?string $kb_nendo = null,
        ?string $cd_bumon = null,
        ?string $kb_cyumon = null,
        ?string $no_cyu = null,
        ?string $no_ko = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT *
                    , CASE WHEN yn_tov = 0
                        THEN 0
                        ELSE (CAST(yn_tov AS FLOAT) - CAST(yn_pcyunyu AS FLOAT))
                            / CAST(yn_tov AS FLOAT) * 100
                        END AS ri_psoneki
                    , CASE WHEN yn_tov = 0
                        THEN 0
                        ELSE (CAST(yn_tov AS FLOAT) - CAST(yn_ycyunyu AS FLOAT))
                            / CAST(yn_tov AS FLOAT) * 100
                        END AS ri_ysoneki
                    , CASE WHEN yn_tov = 0
                        THEN 0
                        ELSE (CAST(yn_tov AS FLOAT) - CAST(yn_rcyunyu AS FLOAT))
                            / CAST(yn_tov AS FLOAT) * 100
                        END AS ri_rsoneki
                FROM (
                    SELECT SUM(yn_tov) AS yn_tov
                        , SUM(tm_pcyokka) AS tm_pcyokka
                        , SUM(yn_pcyokka) AS yn_pcyokka
                        , SUM(yn_pcyokuzai) AS yn_pcyokuzai
                        , SUM(yn_petc) AS yn_petc
                        , SUM(tm_ycyokka) AS tm_ycyokka
                        , SUM(yn_ycyokka) AS yn_ycyokka
                        , SUM(yn_ycyokuzai) AS yn_ycyokuzai
                        , SUM(yn_yetc) AS yn_yetc
                        , SUM(tm_rcyokka) AS tm_rcyokka
                        , SUM(yn_rcyokka) AS yn_rcyokka
                        , SUM(yn_rcyokuzai) AS yn_rcyokuzai
                        , SUM(yn_retc) AS yn_retc
                        , SUM(yn_pcyunyu) AS yn_pcyunyu
                        , SUM(yn_ycyunyu) AS yn_ycyunyu
                        , SUM(yn_rcyunyu) AS yn_rcyunyu
                        , SUM(yn_psoneki) AS yn_psoneki
                        , SUM(yn_ysoneki) AS yn_ysoneki
                        , SUM(yn_rsoneki) AS yn_rsoneki
                    FROM {$this->schema}
                    WHERE (kb_nendo = :kb_nendo
                        AND cd_bumon = :cd_bumon
                        AND kb_cyumon = :kb_cyumon
                        AND no_cyu = :no_cyu
                        AND no_ko = :no_ko
                        ) IS NOT FALSE
                ) A
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->bindValue(':cd_bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->bindValue(':kb_cyumon', $kb_cyumon, PDO::PARAM_STR);
        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   月別集計リスト
    *
    *   @param ?string $kb_nendo 年度
    *   @param ?string $cd_bumon 部門
    *   @return mixed[] 集計値
    */
    public function getMonAggregate(
        ?string $kb_nendo,
        ?string $cd_bumon
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT *
                    ,CASE WHEN yn_tov = 0 THEN 0
                        ELSE yn_psoneki / yn_tov * 100
                        END AS ri_psoneki
                    ,CASE WHEN yn_tov = 0 THEN 0
                        ELSE yn_ysoneki / yn_tov * 100
                        END AS ri_ysoneki
                    ,CASE WHEN yn_tov = 0 THEN 0
                        ELSE yn_rsoneki / yn_tov * 100
                        END AS ri_rsoneki
                FROM (
                    SELECT dt_pkansei_m AS dt_yyyymm
                        , SUM(yn_tov) AS yn_tov
                        , SUM(tm_pcyokka) AS tm_pcyokka
                        , SUM(yn_pcyokka) AS yn_pcyokka
                        , SUM(yn_pcyokuzai) AS yn_pcyokuzai
                        , SUM(yn_petc) AS yn_petc

                        , SUM(tm_ycyokka) AS tm_ycyokka
                        , SUM(yn_ycyokka) AS yn_ycyokka
                        , SUM(yn_ycyokuzai) AS yn_ycyokuzai
                        , SUM(yn_yetc) AS yn_yetc

                        , SUM(tm_rcyokka) AS tm_rcyokka
                        , SUM(yn_rcyokka) AS yn_rcyokka
                        , SUM(yn_rcyokuzai) AS yn_rcyokuzai
                        , SUM(yn_retc) AS yn_retc

                        , SUM(yn_pcyunyu) AS yn_pcyunyu
                        , SUM(yn_ycyunyu) AS yn_ycyunyu
                        , SUM(yn_rcyunyu) AS yn_rcyunyu
                        , SUM(yn_psoneki) AS yn_psoneki
                        , SUM(yn_ysoneki) AS yn_ysoneki
                        , SUM(yn_rsoneki) AS yn_rsoneki

                    FROM public.koban_inf
                    WHERE cd_bumon LIKE :bumon
                        AND kb_nendo LIKE :nendo
                    GROUP BY dt_pkansei_m
                    ORDER BY dt_pkansei_m
                ) A
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $bumon = $cd_bumon . '%';
        $nendo = $kb_nendo . '%';

        $stmt->bindValue(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->bindValue(':nendo', $nendo, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
