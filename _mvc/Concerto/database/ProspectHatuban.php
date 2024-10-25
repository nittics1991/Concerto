<?php

/**
*   prospect_hatuban
*
*   @version 230928
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class ProspectHatuban extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.prospect_hatuban';

    /**
    *   getNewCyuban
    *
    *   @param string $kb_nendo
    *   @param string $cd_bumon
    *   @return string
    */
    public function getNewCyuban(
        string $kb_nendo,
        string $cd_bumon
    ): string {
        $sql = "
            INSERT INTO public.prospect_hatuban AS NEW (
                kb_nendo, no_seq
            ) VALUES (
                :kb_nendo, 1::SMALLINT
            ) ON CONFLICT (kb_nendo)
            DO UPDATE
                SET no_seq = (
                    SELECT no_seq + 1
                    FROM public.prospect_hatuban
                    WHERE kb_nendo = :kb_nendo
            )
            WHERE NEW.kb_nendo = :kb_nendo
            RETURNING no_seq
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':kb_nendo', $kb_nendo, PDO::PARAM_STR);
        $stmt->execute();
        $no_seq_new = $stmt->fetchColumn(0);

        return mb_substr($cd_bumon, 0, 3) .
            mb_substr($kb_nendo, 2, 3) .
            $this->seqNoToProspectNo(
                intval($no_seq_new),
            );
    }

    /**
    *   seqNoToProspectNo
    *
    *   @param int $no_seq
    *   @return string
    */
    private function seqNoToProspectNo(
        int $no_seq,
    ): string {
        return sprintf(
            "%'02.2s",
            mb_strtoupper(
                base_convert((string)$no_seq, 10, 36)
            ),
        );
    }

    /**
    *   prospectNoToSeqNo
    *
    *   @param string $no_cyu
    *   @return int
    */
    public function prospectNoToSeqNo(
        string $no_cyu
    ): int {
        return (int)base_convert(
            mb_substr($no_cyu, 6, 2),
            36,
            10
        );
    }
}
