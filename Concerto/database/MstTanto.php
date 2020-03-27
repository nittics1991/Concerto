<?php

/**
*   mst_tanto
*
*   @version 200325
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstTanto extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_tanto';
    
    /**
    *   現在担当リスト
    *
    *   @param string|null $cd_bumon 部門コード
    *   @return array [[]]
    */
    public function getTantoList($cd_bumon = null)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND cd_bumon = :bumon
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
    
    /**
    *   指定部門優先担当リスト
    *
    *   @param string|null $cd_bumon 部門コード
    *   @return array [[cd_tanto, nm_tanto]]
    */
    public function getTantoListPriotityBumon($cd_bumon = null)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt1, $stmt2;
        
        if (is_null($stmt1)) {
            $sql = "
                SELECT cd_tanto
                    , nm_tanto 
                FROM {$this->schema} 
                WHERE cd_bumon != '' 
                ORDER BY disp_seq 
            ";
            
            $stmt1 = $this->pdo->prepare($sql);
        }
        
        $stmt1->execute();
        $list1 = $stmt1->fetchAll();
        
        if (is_null($cd_bumon)) {
            return $list1;
        } else {
            if (is_null($stmt2)) {
                $sql = "
                    SELECT cd_tanto
                        , nm_tanto 
                    FROM {$this->schema} 
                    WHERE cd_bumon != '' 
                        AND cd_bumon = :bumon
                    ORDER BY disp_seq 
                ";
                
                $stmt2 = $this->pdo->prepare($sql);
            }
            
            $stmt2->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
            $stmt2->execute();
            $list2 = $stmt2->fetchAll();
            
            return array_merge($list2, $list1);
        }
    }
    
    /**
    *   指定部門担当リスト
    *
    *   @param string|null $cd_bumon 部門コード
    *   @return array [[cd_tanto, nm_tanto]]
    */
    public function getTantoListSpecifyBumon($cd_bumon = null)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                SELECT cd_tanto
                    , nm_tanto 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND cd_bumon = :bumon
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
    
    /**
    *   指定部門担当－統一ユーザIDリスト
    *
    *   @param string|null $cd_bumon 部門コード
    *   @return array [[cd_tanto, cd_user]]
    */
    public function getTantoIdListSpecifyBumon($cd_bumon = null)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                SELECT cd_tanto
                    , username AS cd_user 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND cd_bumon = :bumon
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
    
    
    /**
    *   メールリスト
    *
    *   @param string|null $cd_bumon 部門
    *   @return array [[]]
    */
    public function getMailList($cd_bumon = null)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt1, $stmt2;
        
        if (is_null($stmt1)) {
            $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND fg_mail = '1'
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";
            
            $stmt1 = $this->pdo->prepare($sql);
        }
        
        $stmt1->execute();
        $list1 = $stmt1->fetchAll();
        
        if (is_null($cd_bumon)) {
            return $list1;
        } else {
            if (is_null($stmt2)) {
                $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (cd_bumon = :bumon 
                    AND fg_mail = '1'
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";
                
                $stmt2 = $this->pdo->prepare($sql);
            }
            
            $stmt2->bindParam(':bumon', $cd_bumon, PDO::PARAM_STR);
            $stmt2->execute();
            $list2 = $stmt2->fetchAll();
            
            return array_merge($list2, $list1);
        }
    }
}
