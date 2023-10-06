<?php

/**
*   cyuban_inf
*
*   @version 230920
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class CyubanInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.cyuban_inf';

    /**
    *   kb_keikaku更新
    *
    *   @param string $no_cyu
    *   @return void
    */
    public function updateKbKeikaku(
        string $no_cyu
    ): void {
        $selectSql = "
            SELECT COUNT(*) AS cnt
            FROM public.cyunyu_inf
            WHERE no_cyu = :no_cyu
                AND kb_cyunyu= '0'
            GROUP BY no_cyu
        ";
        $selectStmt = $this->pdo->prepare($selectSql);

        $updateSql = "
            UPDATE {$this->name} SET
                kb_keikaku = :kb_keikaku 
            WHERE no_cyu = :no_cyu
        ";
        $updateStmt = $this->pdo->prepare($updateSql);

        $selectStmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $selectStmt->execute();

        $kb_keikaku = $selectStmt->fetchColumn() > 0 ? '1' : '0';

        $updateStmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $updateStmt->bindValue(':kb_keikaku', $kb_keikaku, PDO::PARAM_STR);
        $updateStmt->execute();
    }

    /**
    *   年度リスト
    *
    *   @return mixed[]
    */
    public function getNendoList(): array
    {
        $sql = "
            SELECT DISTINCT kb_nendo
            FROM {$this->schema}
            WHERE kb_nendo != ''
            ORDER BY kb_nendo DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   注番データ
    *
    *   @param string $no_cyu
    *   @return mixed[]
    */
    public function getCyubanData(
        string $no_cyu
    ): array {
        $sql = "
            SELECT DISTINCT no_cyu, kb_nendo, cd_bumon
                , dt_puriage, dt_uriage
                , kb_ukeoi, kb_cyumon, nm_syohin, nm_setti, nm_user
                , kb_keikaku, no_seq, dt_hatuban, nm_tanto, dt_hakkou
                , yn_sp, yn_net
            FROM {$this->schema}
            WHERE no_cyu = :cyuban
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
