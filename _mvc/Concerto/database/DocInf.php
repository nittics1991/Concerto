<?php

/**
*   doc_inf
*
*   @version 210610
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class DocInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.doc_inf';

    /**
    *   最新n個データ取得
    *
    *   @param int $count 個数
    *   @return array [[]]
    */
    public function getData(int $count = 10): array
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT ins_date, nm_doc_inf 
                FROM {$this->schema} 
                ORDER BY ins_date DESC 
                OFFSET 0 LIMIT :count 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':count', $count, PDO::PARAM_INT);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
