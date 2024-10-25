<?php

/**
*   claim_inf
*
*   @version 231107
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\FiscalYear;

class ClaimInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.claim_inf';

    /**
    *   generateNo
    *
    *   @param string $kb_nendo
    *   @return string
    */
    public function generateNo(
        string $kb_nendo,
    ): string {
        $sql = "
            SELECT MAX(no_seq) AS no_seq
            FROM public.claim_inf
            WHERE kb_nendo = :kb_nendo
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->execute();

        $no_seq = (int)$stmt->fetchColumn() + 1;

        return 'CLM' .
            mb_substr($kb_nendo, 2) .
            sprintf('%03d', $no_seq);
    }
}
