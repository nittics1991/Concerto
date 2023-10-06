<?php

/**
*   mitumori_inf
*
*   @version 230117
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MitumoriInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mitumori_inf';

    /**
    *   年度リスト
    *
    *   @param ?string $cd_bumon
    *   @return mixed[] [[kb_nendo]...]
    */
    public function getNendoList(
        ?string $cd_bumon
    ): array {
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
    public function generateNo(
        string $kb_nendo,
        string $cd_code
    ): string {
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
