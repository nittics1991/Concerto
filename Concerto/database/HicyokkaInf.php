<?php

/**
*   hicyokka_inf
*
*   @version 210709
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use PDO;
use Concerto\standard\ModelDb;

class HicyokkaInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.hicyokka_inf';

    /**
    *   分類リスト初期値
    *
    *   @var array
    */
    private array $initial_bunrui_list = [
        '見積',
        'トラブル',
        '調査',
        '問合せ',
    ];
    
    /**
    *   分類リスト
    *
    *   @param  string $cd_system
    *   @return array
    */
    public function bunruiList(
        string $cd_system,
    ): array {
        $sql = "
            SELECT DISTINCT nm_bunrui
            FROM public.hicyokka_inf A
            JOIN (
                SELECT cd_bumon
                FROM symphony.bumon_group
                WHERE cd_system = :cd_system
            ) B
                ON B.cd_bumon = A.cd_bumon
            ORDER BY nm_bunrui
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cd_system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return array_merge(
            $initial_bunrui_list,
            array_column($result, 'nm_bunrui'),
        );
    }
    
    /**
    *   年月リスト
    *
    *   @return array
    */
    public function yyyymmList(): array
    {
        $sql = "
            SELECT DISTINCT dt_yyyymm
            FROM public.hicyokka_inf
            ORDER BY dt_yyyymm DESC
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = (return)$stmt->fetchAll();
    }

    /**
    *   注番コード生成
    *
    *   @param string $cd_bumon
    *   @param string $dt_yyyymm
    *   @return string
    */
    public function generateCyubanCode(
        string $cd_bumon,
        string $dt_yyyymm,
    ): string {
        $bumon = trim(mb_substr($cd_bumon, 0, 3));
        if (mb_strlen($cd_bumon) !== 3) {
            throw new InvalidArgumentException(
                "invalid cd_bumon:{$cd_bumon}"
            );
        }

        $yyyy = mb_substr($dt_yyyymm, 0, 4);
        $mm = mb_substr($dt_yyyymm, 4, 2);

        if (
            (int)$yyyy < 2020 ||
            (int)$yyyy >= 2100 ||
            (int)$mm < 1 ||
            (int)$mm > 12
        ) {
            throw new InvalidArgumentException(
                "invalid dt_yyyymm:{$dt_yyyymm}"
            );
        }

        $yy = mb_substr($yyyy, 2, 2);

        return "{$bumon}{$yy}{$mm}";
    }

    /**
    *   項番コード生成
    *
    *   @param string $cd_bumon
    *   @return string
    */
    public function generateKobanCode(
        string $cd_bumon,
    ): string {
        $bumon = trim(mb_substr($cd_bumon, 0, 3));
        if (mb_strlen($cd_bumon) !== 3) {
            throw new InvalidArgumentException(
                "invalid cd_bumon:{$cd_bumon}"
            );
        }

        $buka = mb_substr($bumon, 1, 2);

        return "{$buka}01";
    }

    /**
    *   連番生成
    *
    *   @param string $no_cyu
    *   @param string $no_ko
    *   @return int
    */
    public function generateNoSeq(
        string $no_cyu,
        string $no_ko
    ): int {
        $sql = "
            SELECT MAX(no_seq) AS no_seq
            FROM public.hicyokka_inf
            WHERE no_cyu = :no_cyu
                AND no_ko = :no_ko
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':no_cyu', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':no_ko', $no_ko, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchColumn();
        return empty($result) ? 0 : $result + 1;
    }
}
