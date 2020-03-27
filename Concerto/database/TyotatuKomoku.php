<?php

/**
*   tyotatu_komoku
*
*   @version 150609
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
    *   @param text $no_cyu 注番
    *   @return int
    */
    public function getMaxNoSheet($no_cyu)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_sheet) AS no_sheet 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_sheet'])) ? 0 : $result['no_sheet'];
    }
    
    /**
    *   no_line最大値取得
    *
    *   @param text $no_cyu 注番
    *   @param int $no_sheet シート
    *   @return int
    */
    public function getMaxNoLine($no_cyu, $no_sheet)
    {
        /**
        *   プリペア
        *
        *   @var resorce
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
        
        $stmt->bindParam(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':sheet', $no_sheet, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_line'])) ?       0 : $result['no_line'];
    }
    
    /**
    *   no_rev最大値取得
    *
    *   @param text $no_cyu 注番
    *   @param int $no_sheet シート
    *   @param int $no_line 行
    *   @return int
    */
    public function getMaxNoRev($no_cyu, $no_sheet, $no_line)
    {
        /**
        *   プリペア
        *
        *   @var resorce
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
        
        $stmt->bindParam(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':sheet', $no_sheet, PDO::PARAM_INT);
        $stmt->bindParam(':line', $no_line, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_rev'])) ? 0 : $result['no_rev'];
    }
    /**
    *   レコードコピー
    *
    *   @param text $no_cyu_src コピー元注番
    *   @param text $no_sheet_src コピー元シート番号
    *   @param text $no_cyu_dst コピー先注番
    *   @param text $no_sheet_dst コピー先シート番号
    */
    public function copyTyotatuKomoku(
        $no_cyu_src,
        $no_sheet_src,
        $no_cyu_dst,
        $no_sheet_dst
    ) {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                INSERT INTO {$this->schema} 
                (
                
                
                
                
                
                
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':cyuban1', $no_cyu_src, PDO::PARAM_STR);
        $stmt->bindParam(':sheet1', $no_sheet_src, PDO::PARAM_INT);
        $stmt->bindParam(':cyuban2', $no_cyu_dst, PDO::PARAM_STR);
        $stmt->bindParam(':sheet2', $no_sheet_dst, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
