<?php

/**
*   mitumori_doc
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MitumoriDoc extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mitumori_doc';

    /**
    *   no_seq最大値取得
    *
    *   @param string $no_mitumori
    *   @return int
    */
    public function getMaxNoSeq($no_mitumori)
    {
        $sql = "
            SELECT MAX(no_seq) AS no_seq 
                FROM {$this->schema} 
                WHERE no_mitumori = :mitumori
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':mitumori', $no_mitumori, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }
}
