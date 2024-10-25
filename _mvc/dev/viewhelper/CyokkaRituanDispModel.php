<?php

/**
*   FacadeModel
*
*   @version 210615
*/

declare(strict_types=1);

namespace cyokka_rituan2\model;

use PDO;
use dev\database\{
    CyokkaKeikaku,
    CyokkaMonKeikaku,
};
use dev\FiscalYear;

class CyokkaRituanDispModel
{
    /**
    *   object
    *
    *   @var object
    */
    private $pdo;
    private $cyokkaKeikaku;
    private $cyokkaMonKeikaku;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param CyokkaKeikaku $cyokkaKeikaku
    *   @param CyokkaMonKeikaku $cyokkaMonKeikaku
    */
    public function __construct(
        PDO $pdo,
        CyokkaKeikaku $cyokkaKeikaku,
        CyokkaMonKeikaku $cyokkaMonKeikaku,
    ) {
        $this->pdo = $pdo;
        $this->cyokkaKeikaku = $cyokkaKeikaku;
        $this->cyokkaMonKeikaku = $cyokkaMonKeikaku;
    }

    /**
    *   部門リスト
    *
    *   @param string $cd_system
    *   @return array
    */
    public function getBumonList(string $cd_system)
    {
        $sql = "
            SELECT A.cd_bumon, A.nm_bumon
            FROM public.mst_bumon A
            JOIN symphony.bumon_group B
                ON B.cd_bumon = A.cd_bumon
            WHERE B.cd_system = :system
            ORDER BY A.cd_bumon
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
    *   過去年度部門リスト
    *
    *   @param string $kb_nendo
    *   @return array
    */
    public function getPastFiscalYearBumonList(
        string $kb_nendo
    ) {
        $sql = "
            SELECT cd_bumon
                , cd_bumon AS nm_bumon
            FROM public.cyokka_keikaku
            WHERE kb_nendo = :nendo
            ORDER BY cd_bumon
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
    *   年度リスト
    *
    *   @return array
    */
    public function getNendoList()
    {
        $sql = "
            SELECT DISTINCT kb_nendo
            FROM public.cyuban_inf
            ORDER BY kb_nendo
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = (array)$stmt->fetchAll();

        $min_nendo = isset($result[0]['kb_nendo']) ?
            $result[0]['kb_nendo'] :
            FiscalYear::getPresentNendo();

        $max_nendo = isset($result[count($result) - 1]['kb_nendo']) ?
            $result[count($result) - 1]['kb_nendo'] :
            FiscalYear::getPresentNendo();

        $nendo_list = [];
        $target_nendo = FiscalYear::getNextNendo($max_nendo);

        while ($min_nendo <= $target_nendo) {
            $nendo_list[] = [
                'kb_nendo' => $target_nendo,
                'nm_nendo' => FiscalYear::nendoCodeToZn($target_nendo),
            ];

            $target_nendo = FiscalYear::getPreviousNendo($target_nendo);
        }

        return $nendo_list;
    }

    /**
    *   直課立案データ
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    */
    public function getCyokkaData($kb_nendo, $cd_bumon)
    {
        $cyokkaKeikakuData = $this->cyokkaKeikaku->createModel();
        $cyokkaKeikakuData->kb_nendo = $kb_nendo;
        $cyokkaKeikakuData->cd_bumon = $cd_bumon;
        $result = $this->cyokkaKeikaku->select($cyokkaKeikakuData);
        return count($result) == 1 ? $result[0] : [];
    }

    /**
    *   直課立案月別データ
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return array
    */
    public function getCyokkaMonList($kb_nendo, $cd_bumon)
    {
        $cyokkaMonKeikakuData = $this->cyokkaMonKeikaku->createModel();
        $cyokkaMonKeikakuData->kb_nendo = $kb_nendo;
        $cyokkaMonKeikakuData->cd_bumon = $cd_bumon;
        $result = $this->cyokkaMonKeikaku->select(
            $cyokkaMonKeikakuData,
            'dt_yyyymm'
        );

        $items = [];
        foreach ($result as $obj) {
            $items[] = $obj->toArray();
        }
        return $items;
    }
}
