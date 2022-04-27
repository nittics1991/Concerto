<?php

/**
*   mitumori_inf
*
*   @version 180627
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MitumoriInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mitumori_inf';

    /**
    *   年度リスト
    *
    *   @param string|null $cd_bumon
    *   @return array [[kb_nendo]...]
    */
    public function getNendoList($cd_bumon)
    {
        $sql = "
            SELECT DISTINCT kb_nendo
            FROM {$this->schema}
            WHERE (
                cd_bumon = :bumon
                ) IS NOT FALSE
            ORDER BY kb_nendo DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   新規採番
    *
    *   @param string $kb_nendo
    *   @param string $cd_code
    *   @return string
    */
    public function generateNo($kb_nendo, $cd_code)
    {
        $sql = "
            SELECT MAX(split_part(no_mitumori, '-', 2)) AS no_mitumori
            FROM {$this->schema}
            WHERE no_mitumori LIKE :code
        ";
        $stmt = $this->pdo->prepare($sql);

        $nendo = mb_substr($kb_nendo, 2, 2);
        $stmt->bindValue(':code', "%-{$nendo}%", PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch();

        if (empty($row['no_mitumori'])) {
            return "{$cd_code}-{$nendo}0001";
        }
        return "{$cd_code}-" . ($row['no_mitumori'] + 1);
    }
}
