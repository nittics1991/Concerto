<?php

/**
*   wf_free
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class WfFree extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.wf_free';

    /**
    *   no_seq最大値取得
    *
    *   @param string $no_cyu
    *   @param int $no_page
    *   @return ?int
    */
    public function getMaxNoSeq(
        string $no_cyu,
        int $no_page
    ): ?int {
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_seq) AS no_seq 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
                        AND no_page = :page
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? null : (int)$result;
    }

    /**
    *   no_seq生成
    *
    *   @param string $no_cyu
    *   @param int $no_page
    *   @return int
    */
    public function generateNewNoSeq(
        string $no_cyu,
        int $no_page
    ): int {
        return is_null($this->getMaxNoSeq($no_cyu, $no_page)) ?
            0 : $this->getMaxNoSeq($no_cyu, $no_page) + 1;
        ;
    }
}
