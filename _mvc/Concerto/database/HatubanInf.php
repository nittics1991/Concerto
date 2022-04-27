<?php

/**
*   hatuban_inf
*
*   @version 220308
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\database\HatubanInfData;

class HatubanInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.hatuban_inf';

    /**
    *   発番確認設定
    *
    *   @param string $no_cyu
    *   @param string $dt_hatuban
    *   @param string $cd_tanto
    */
    public function setConfirmationBy(
        string $no_cyu,
        string $dt_hatuban,
        string $cd_tanto
    ) {
        if ($this->latestStatus($no_cyu) === []) {
            return;
        }
        
        $data = $this->createModel();
        $data->no_cyu = $no_cyu;
        $data->dt_hatuban = $dt_hatuban;
        $data->cd_tanto = $cd_tanto;
        $data->dt_kakunin = date('Ymd');

        $this->insert([$data]);
    }

    /**
    *   発番確認解除
    *
    *   @param string $no_cyu
    *   @param string $dt_hatuban
    */
    public function unsetConfirmationBy(
        string $no_cyu,
        string $dt_hatuban
    ) {
        if ($this->latestStatus($no_cyu) === []) {
            return;
        }
        
        $where = $this->createModel();
        $where->no_cyu = $no_cyu;
        $where->dt_hatuban = $dt_hatuban;

        $this->delete([$where]);
    }

    /**
    *   最新確認状態
    *
    *   @param string $no_cyu
    *   @return array
    */
    public function latestStatus(
        string $no_cyu
    ): array {
        $sql = "
            SELECT  A.*
                , B.cd_tanto, B.dt_kakunin
            FROM (
                SELECT no_cyu, dt_hatuban
                FROM public.cyuban_inf
                WHERE no_cyu = :cyuban
            ) A
            LEFT JOIN (
                SELECT *
                FROM {$this->schema}
                WHERE no_cyu = :cyuban
            ) B
                ON B.no_cyu = A.no_cyu
                    AND B.dt_hatuban = A.dt_hatuban
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue('cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result === false? []:$result;
    }
}
