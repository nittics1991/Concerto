<?php

/**
*   tyotatu_komoku
*
*   @version 211008
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
    *   @param string $no_cyu
    *   @param int $no_page
    *   @return int
    */
    public function getMaxNoSheet(
        string $no_cyu,
        int $no_page,
    ): int {
        /**
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_sheet) AS no_sheet 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban
                        AND no_page = :page
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }

    /**
    *   no_line最大値取得
    *
    *   @param string $no_cyu
    *   @param int $no_page
    *   @param int $no_sheet
    *   @return int
    */
    public function getMaxNoLine(
        string $no_cyu,
        int $no_page,
        int $no_sheet,
    ): int {
        /**
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_line) AS no_line 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
                        AND no_page = :page
                        AND no_sheet = :sheet
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':page', $no_page, PDO::PARAM_INT);
        $stmt->bindValue(':sheet', $no_sheet, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }

    /**
    *   no_rev最大値取得
    *
    *   @param string $no_cyu 注番
    *   @param int $no_page
    *   @param int $no_sheet シート
    *   @param int $no_line 行
    *   @return int
    */
    public function getMaxNoRev(
        string $no_cyu,
        int $no_page,
        int $no_sheet,
        int $no_line,
    ): int {
        /**
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_rev) AS no_rev 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
                        AND no_page = :page
                        AND no_sheet = :sheet
                        AND no_line = :line 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindValue(':page', $no_page, PDO::PARAM_INT);
        $stmt->bindValue(':sheet', $no_sheet, PDO::PARAM_INT);
        $stmt->bindValue(':line', $no_line, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? 0 : (int)$result;
    }
}
