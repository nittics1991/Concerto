<?php

/**
*   tyotatu_komoku
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class TyotatuKomoku extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.tyotatu_komoku';

    /**
    *   no_sheet最大値取得
    *
    *   @param string $no_cyu 注番
    *   @return int
    */
    public function getMaxNoSheet($no_cyu)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_sheet) AS no_sheet 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }

    /**
    *   no_line最大値取得
    *
    *   @param string $no_cyu 注番
    *   @param int $no_sheet シート
    *   @return int
    */
    public function getMaxNoLine($no_cyu, $no_sheet)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_line) AS no_line 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
                        AND no_sheet = :sheet
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':sheet', $no_sheet, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }

    /**
    *   no_rev最大値取得
    *
    *   @param string $no_cyu 注番
    *   @param int $no_sheet シート
    *   @param int $no_line 行
    *   @return int
    */
    public function getMaxNoRev($no_cyu, $no_sheet, $no_line)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_rev) AS no_rev 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
                        AND no_sheet = :sheet
                        AND no_line = :line 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':sheet', $no_sheet, PDO::PARAM_INT);
        $stmt->bindValue(':line', $no_line, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }
}
