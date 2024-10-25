<?php

/**
*   hatuban_inf
*
*   @version 230227
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\database\HatubanInfData;

class HatubanInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.hatuban_inf';

    /**
    *   発番確認設定
    *
    *   @param string $no_cyu
    *   @param string $dt_hatuban
    *   @param string $cd_tanto
    *   @return void
    */
    public function setConfirmationBy(
        string $no_cyu,
        string $dt_hatuban,
        string $cd_tanto
    ): void {
        $sql = "
            INSERT INTO public.hatuban_inf (
                no_cyu, dt_hatuban, cd_tanto, dt_kakunin
            ) VALUES (
                :no_cyu, :dt_hatuban, :cd_tanto, :dt_kakunin
            )
            ON CONFLICT (no_cyu) DO UPDATE
            SET dt_hatuban = :dt_hatuban
                , dt_kakunin = :dt_kakunin
                , cd_tanto = :cd_tanto
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':dt_hatuban', $dt_hatuban, PDO::PARAM_STR);
        $stmt->bindValue(':cd_tanto', $cd_tanto, PDO::PARAM_STR);

        $dt_kakunin = date('Ymd');
        $stmt->bindValue(':dt_kakunin', $dt_kakunin, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
    *   latestData
    *
    *   @param string $no_cyu
    *   @return mixed[]
    */
    public function latestData(
        string $no_cyu,
    ): array {
        $sql = "
            SELECT *
            FROM public.hatuban_inf
            WHERE no_cyu = :no_cyu
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);

        $stmt->execute();

        return (array)$stmt->fetch();
    }
}
