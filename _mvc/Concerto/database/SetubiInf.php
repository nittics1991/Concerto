<?php

/**
*   setubi_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\{
    ModelData,
    ModelDb
};

class SetubiInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.setubi_inf';

    /**
    *   MaxID
    *
    *   @return string
    */
    public function getMaxId(): string
    {
        $sql = "
            SELECT MAX(cd_setubi) AS cd_setubi
            FROM {$this->schema}
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return (!isset($result['cd_setubi'])) ?
            0 : $result['cd_setubi'];
    }

    /**
    *   NewID
    *
    *   @return string
    */
    public function getNewId(): string
    {
        $maxId = (int)$this->getMaxId();
        return sprintf("%04d", $maxId + 1);
    }

    /**
    *   分類リスト
    *
    *   @param ?string $cd_group
    *   @return mixed[]
    */
    public function getBunruiList(
        ?string $cd_group = null
    ): array {
        $sql = "
            SELECT nm_bunrui
            FROM public.setubi_inf
        ";

        if (!is_null($cd_group)) {
            $sql .= "  WHERE cd_group = :setubi";
        }

        $sql .= "
            GROUP BY nm_bunrui
            ORDER BY nm_bunrui
        ";

        $stmt = $this->pdo->prepare($sql);

        if (!is_null($cd_group)) {
            $stmt->bindValue(':setubi', $cd_group, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = (array)$stmt->fetchAll();
        $items = [];

        foreach ($result as $list) {
            $items[] = ['nm_bunrui' => $list['nm_bunrui']];
        }
        return $items;
    }

    /**
    *   名称リスト
    *
    *   @param ?string $cd_group
    *   @return mixed[]
    */
    public function getSetubiNameList(
        ?string $cd_group = null
    ): array {
        $sql = "
            SELECT nm_setubi
            FROM public.setubi_inf
        ";

        if (!is_null($cd_group)) {
            $sql .= "  WHERE cd_group = :setubi";
        }

        $sql .= "
            GROUP BY nm_setubi
            ORDER BY nm_setubi
        ";

        $stmt = $this->pdo->prepare($sql);

        if (!is_null($cd_group)) {
            $stmt->bindValue(':setubi', $cd_group, PDO::PARAM_STR);
        }

        $stmt->execute();
        $result = (array)$stmt->fetchAll();
        $items = [];

        foreach ($result as $list) {
            $items[] = ['nm_setubi' => $list['nm_setubi']];
        }
        return $items;
    }

    /**
    *   部門リスト
    *
    *   @return mixed[]
    */
    public function getBumonList(): array
    {
        $sql = "
            SELECT cd_bumon, nm_bumon
            FROM (
                SELECT cd_bumon
                FROM public.setubi_inf
                GROUP BY cd_bumon
            )A
            JOIN public.mst_bumon B
                ON B.cd_bumon = A.cd_bumon
            ORDER BY cd_bumon
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
