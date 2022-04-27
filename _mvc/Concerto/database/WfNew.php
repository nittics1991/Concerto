<?php

/**
*   wf_new
*
*   @version 211006
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use PDO;
use Concerto\standard\ModelDb;
use Concerto\database\WfNewData;

class WfNew extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.wf_new';

    /**
    *   no_page最大値取得
    *
    *   @param string $no_cyu
    *   @return ?int
    */
    public function getMaxNoPage(string $no_cyu): ?int
    {
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_page) AS no_page 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? null : $result;
    }

    /**
    *   no_page生成
    *
    *   @param string $no_cyu
    *   @return int
    */
    public function generateNewNoPage(string $no_cyu): int
    {
        return is_null($this->getMaxNoPage($no_cyu)) ?
            0 : $this->getMaxNoPage($no_cyu) + 1;
        ;
    }

    /**
    *   出荷番号記号prefixから出荷番号最大値取得
    *
    *   @param string $syukka_key 出荷番号記号
    *   @return int 最大番号
    */
    public function getMaxNoSyukkaByPrefix(string $syukka_key): int
    {
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT MAX(nm_cd_syukka) AS nm_cd_syukka 
                FROM {$this->schema} 
                WHERE nm_cd_syukka LIKE :key || '%'
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $key = "{$syukka_key}%";
        $stmt->bindValue(':key', $key, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return empty($result) ?
            0 : (int)mb_substr($result, 2);
    }

    /**
    *   出荷コード生成
    *
    *   @return string
    */
    public function generateNmCdSyukkaCode(): string
    {
        $yyyy = (int)date('Y') - 2012;
        $mm = (int)date('m');

        if ($yyyy < 0 || $yyyy >= 26) {
            throw new InvalidArgumentException(
                "can not make code. not support year"
            );
        }

        if ($mm >= 4 && $mm <= 9) {
            $prefix = chr($yyyy + 65) . 'K';
        } elseif ($mm >= 10 && $mm <= 12) {
            $prefix = chr($yyyy + 65) . 'S';
        } else {
            $prefix = chr($yyyy - 1 + 65) . 'S';
        }

        return $prefix;
    }

    /**
    *   出荷記号生成
    *
    *   @return string
    */
    public function generateNmCdSyukka(): string
    {
        $code = $this->generateNmCdSyukkaCode();
        $new_no = $this->getMaxNoSyukkaByPrefix($code) + 1;
        return $code . sprintf('%03d', $new_no);
    }
}
