<?php

/**
*   wf_gijiroku
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class WfGijiroku extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.wf_gijiroku';

    /**
    *   nm_basyoリスト
    *
    *   @param string $dt_start 開始yyyymmdd
    *   @param string $dt_end 終了yyyymmdd
    *   @return mixed[]
    */
    public function getBasyoList(
        string $dt_start,
        string $dt_end
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT DISTINCT nm_basyo 
                FROM {$this->schema} 
                WHERE dt_kaisai BETWEEN :start AND :end
                ORDER BY nm_basyo 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':start', $dt_start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $dt_end, PDO::PARAM_STR);
        $stmt->execute();

        $result = (array)$stmt->fetchAll();
        $items = [];

        foreach ((array)$result as $val) {
            $items[] = $val;
        }
        return $items;
    }
}
