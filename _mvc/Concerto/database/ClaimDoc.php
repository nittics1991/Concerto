<?php

/**
*   claim_doc
*
*   @version 231102
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class ClaimDoc extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.claim_doc';

    /**
    *   no_seq最大値取得
    *
    *   @param string $no_claim
    *   @return int
    */
    public function getMaxNoSeq(
        string $no_claim
    ): int {
        $sql = "
            SELECT MAX(no_seq) AS no_seq 
                FROM {$this->schema} 
                WHERE no_claim = :no_claim
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':no_claim', $no_claim, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }
}
