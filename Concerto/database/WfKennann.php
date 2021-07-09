<?php

/**
*   wf_kennann
*
*   @version 210610
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class WfKennann extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.wf_kennann';

    /**
    *   no_seq最大値取得
    *
    *   @param string $no_cyu 注番
    *   @param int $no_page ページ
    *   @return int
    */
    public function getMaxNoSeq($no_cyu, $no_page)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_seq) AS no_seq 
                    FROM {$this->schema} 
                    WHERE no_cyu = :no_cyu 
                    AND no_page = :no_page 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }

    /**
    *   未完リスト取得
    *
    *   @param bool $past true:確認日経過のみ
    *   @return array
    */
    public function getMikanList($past = true)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (dt_kakunin = '' 
                    OR dt_kakunin IS NULL) 
                    AND dt_kigen < :date 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $date = ($past) ? date('Ymd') : '00000000';

        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
