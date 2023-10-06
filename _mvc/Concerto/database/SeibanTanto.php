<?php

/**
*   seiban_tanto
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\database\SeibanTantoData;

class SeibanTanto extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.seiban_tanto';

    /**
    *   担当設定更新
    *
    *   @param string $no_cyu
    *   @param ?string $no_ko
    *   @param ?string $no_seq
    *   @return void
    */
    public function updateBy(
        string $no_cyu,
        ?string $no_ko = null,
        ?string $no_seq = null
    ): void {
        $this->deleteWithExceptThoseMarkedManually(
            $no_cyu,
            $no_ko,
            $no_seq
        );

        $sql = "
            INSERT INTO {$this->schema} (
                ins_date, no_cyu, no_ko, no_seq, cd_tanto
            )
            SELECT
                TO_CHAR(NOW(), 'YYYYMMDD HH24MISS')
                , no_cyu
                , no_ko
                , no_seq
                , cd_tanto
            FROM public.cyunyu_inf
            WHERE (cd_genka_yoso = 'C1'
                AND kb_cyunyu = '0'
                AND no_cyu = :cyuban
                AND no_ko = :koban
                AND no_seq = :seq
            ) IS NOT FALSE
            GROUP BY no_cyu, no_ko, no_seq, cd_tanto
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue('koban', $no_ko, PDO::PARAM_STR);
        $stmt->bindValue('seq', $no_seq, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
    *   手動設定を除き削除
    *
    *   @param string $no_cyu
    *   @param ?string $no_ko
    *   @param ?string $no_seq
    *   @return void
    */
    public function deleteWithExceptThoseMarkedManually(
        string $no_cyu,
        ?string $no_ko = null,
        ?string $no_seq = null
    ): void {
        $sql = "
            DELETE FROM {$this->schema}
            WHERE (no_seq != :manual
                AND no_cyu = :cyuban
                AND no_ko = :koban
                AND no_seq = :seq
            ) IS NOT FALSE
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(
            'manual',
            SeibanTantoData::MANUAL,
            PDO::PARAM_STR
        );
        $stmt->bindValue('cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue('koban', $no_ko, PDO::PARAM_STR);
        $stmt->bindValue('seq', $no_seq, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
    *   担当設定状態
    *
    *   @param string $no_cyu
    *   @param string $cd_tanto
    *   @return bool
    */
    public function isSetTanto(
        string $no_cyu,
        string $cd_tanto
    ): bool {
        $where = $this->seibanTanto->createModel();
        $where->no_cyu = $no_cyu;
        $where->cd_tanto = $cd_tanto;
        $result = $this->seibanTanto->select($where);
        return count($result) > 0;
    }

    /**
    *   手動担当設定
    *
    *   @param string $no_cyu
    *   @param string $cd_tanto
    *   @return void
    */
    public function markManually(
        string $no_cyu,
        string $cd_tanto
    ): void {
        $data = $this->createModel();
        $data->no_cyu = $no_cyu;
        $data->cd_tanto = $cd_tanto;
        $data->no_ko = '';
        $data->no_seq = SeibanTantoData::MANUAL;
        $data->ins_date = date('Ymd His');

        $this->insert([$data]);
    }

    /**
    *   手動担当解除
    *
    *   @param string $no_cyu
    *   @param string $cd_tanto
    *   @return void
    */
    public function unmarkManually(
        string $no_cyu,
        string $cd_tanto
    ): void {
        $where = $this->createModel();
        $where->no_cyu = $no_cyu;
        $where->cd_tanto = $cd_tanto;
        $where->no_seq = SeibanTantoData::MANUAL;

        $this->delete([$where]);
    }

    /**
    *   手動担当設定状態
    *
    *   @param string $no_cyu
    *   @param string $cd_tanto
    *   @return bool
    */
    public function isSetTantoManually(
        string $no_cyu,
        string $cd_tanto
    ): bool {
        $where = $this->createModel();
        $where->no_cyu = $no_cyu;
        $where->cd_tanto = $cd_tanto;
        $where->no_seq = SeibanTantoData::MANUAL;
        $result = $this->select($where);
        return count($result) > 0;
    }
}
