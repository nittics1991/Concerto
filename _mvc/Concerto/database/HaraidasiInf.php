<?php

/**
*   haraidasi_inf
*
*   @version 170605
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class HaraidasiInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.haraidasi_inf';

    /**
    *   getNmAdrUnique
    *
    *   @return array
    */
    public function getNmAdrUnique()
    {
        $sql = "
            SELECT DISTINCT nm_adr
            FROM public.haraidasi_inf
            WHERE nm_adr != ''
            ORDER BY nm_adr
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];

        foreach ($stmt as $list) {
            $result[] = $list['nm_adr'];
        }
        return $result;
    }

    /**
    *   getNoAdrUnique
    *
    *   @return array
    */
    public function getNoAdrUnique()
    {
        $sql = "
            SELECT DISTINCT no_adr
            FROM public.haraidasi_inf
            WHERE no_adr != ''
            ORDER BY no_adr
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];

        foreach ($stmt as $list) {
            $result[] = $list['no_adr'];
        }
        return $result;
    }

    /**
    *   getNoTelUnique
    *
    *   @return array
    */
    public function getNoTelUnique()
    {
        $sql = "
            SELECT DISTINCT no_tel
            FROM public.haraidasi_inf
            WHERE no_tel != ''
            ORDER BY no_tel
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];

        foreach ($stmt as $list) {
            $result[] = $list['no_tel'];
        }
        return $result;
    }

    /**
    *   getNmToUnique
    *
    *   @return array
    */
    public function getNmToUnique()
    {
        $sql = "
            SELECT DISTINCT nm_to
            FROM public.haraidasi_inf
            WHERE nm_to != ''
            ORDER BY nm_to
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = [];

        foreach ($stmt as $list) {
            $result[] = $list['nm_to'];
        }
        return $result;
    }
}
