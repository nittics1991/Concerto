<?php

/**
*   wf_doc
*
*   @version 201130
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class WfDoc extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.wf_doc';

    /**
    *   最新no_seq生成
    *
    *   @param string $no_cyu
    *   @param int $no_page
    *   @return int
    */
    public function generateNewNoSeq(
        string $no_cyu,
        int $no_page
    ): int {
        $sql = "SELECT MAX(no_seq) AS no_seq 
            FROM {$this->schema} 
            WHERE no_cyu = :no_cyu 
            AND no_page = :no_page 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_page', $no_page, PDO::PARAM_INT);
        $stmt->execute();

        $no_seq = $stmt->fetchColumn();
        return isset($no_seq) ? $no_seq + 1 : 0;
    }

    /**
    *   最新cd_job生成
    *
    *   @param string $no_cyu
    *   @param int $no_page
    *   @return string
    */
    public function generateCdJob(
        string $no_cyu,
        int $no_page
    ): string {
        $wfDocData = $this->createModel();

        return $wfDocData->wfDocData->noSeq2CdJob(
            $this->generateNewNoSeq($no_cyu, $no_page)
        );
    }
}
