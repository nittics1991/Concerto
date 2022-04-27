<?php

/**
*   cyuban_inf
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class CyubanInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.cyuban_inf';

    /**
    *   kb_keikaku更新
    *
    *   @param string $no_cyu
    *   @return void
    */
    public function updateKbKeikaku(string $no_cyu): void
    {
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
    *   @return array [kb_nendo]
    */
    public function getNendoList(): array
    {
        $sql = "SELECT DISTINCT kb_nendo
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
    *   @return array
    */
    public function getCyubanData(string $no_cyu): array
    {
        $sql = "
            SELECT DISTINCT no_cyu, kb_nendo, cd_bumon, dt_puriage, dt_uriage
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

    /**
    *   getNewCyuban
    *
    *   @param string $kb_nendo 年度
    *   @param string $cd_bumon 部門
    *   @return string
    */
    public function getNewCyuban(string $kb_nendo, string $cd_bumon): string
    {
        $sql = "
            SELECT MAX(no_cyu) AS no_cyu
            FROM public.cyuban_inf
            WHERE no_cyu LIKE :cyuban
        ";
        $stmt = $this->pdo->prepare($sql);

        $cyuban = mb_substr($cd_bumon, 0, 3) . mb_substr($kb_nendo, 2, 3);
        $stmt->bindValue(':cyuban', "{$cyuban}%", PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);

        if (empty($result)) {
            return "{$cyuban}01";
        }

        $no_cyu = (string)$result;

        return $this->seqNoToProspectNo(
            $cyuban,
            $this->prospectNoToSeqNo($no_cyu) + 1
        );
    }

    /**
    *   prospectNoToSeqNo
    *
    *   @param string $no_cyu 注番
    *   @return int
    */
    public function prospectNoToSeqNo(string $no_cyu): int
    {
        return (int)base_convert(
            mb_substr($no_cyu, 6, 2),
            36,
            10
        );
    }

    /**
    *   seqNoToProspectNo
    *
    *   @param string $no_cyu 注番
    *   @param int $no_seq
    *   @return string
    */
    public function seqNoToProspectNo(string $no_cyu, int $no_seq): string
    {
        $no = '00' . mb_strtoupper(
            base_convert((string)$no_seq, 10, 36)
        );
        return mb_substr($no_cyu, 0, 6) . mb_substr($no, -2);
    }
}
