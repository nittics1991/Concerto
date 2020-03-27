<?php

/**
*   wf_new
*
*   @version 160129
*/

declare(strict_types=1);

namespace Concerto\database;

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
    *   @param text $no_cyu 注番
    *   @return int
    */
    public function getMaxNoPage($no_cyu)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "SELECT MAX(no_page) AS no_page 
                    FROM {$this->schema} 
                    WHERE no_cyu = :cyuban 
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_page'])) ?       0 : $result['no_page'];
    }
    
    /**
    *   no_rev最大値取得
    *
    *   @param text $no_cyu 注番
    *   @param int $no_page ページ
    *   @return int
    */
    public function getMaxNoRev($no_cyu, $no_page)
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
                        AND no_page = :page
            ";
        
            $stmt = $this->pdo->prepare($sql);
        }
        
        $stmt->bindParam(':cyuban', $no_cyu, PDO::PARAM_STR);
        $stmt->bindParam(':page', $no_page, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['no_rev'])) ?        0 : $result['no_rev'];
    }
    
    /**
    *   最大no_revワークフローデータ取得
    *
    *   @param text $no_cyu 注番
    *   @param int $no_page ページ
    *   @return array
    */
    public function getMaxRevWfData($no_cyu, $no_page)
    {
        $wfNewData = new WfNewData();
        $wfNewData->no_cyu  = $no_cyu;
        $wfNewData->no_page     = $no_page;
        $wfNewData->no_rev  = $this->getMaxNoRev($no_cyu, $no_page);
        $result = $this->select($wfNewData);
        
        if (count($result) > 0) {
            $item = $result[0];
            return $item->toArray();
        }
        return [];
    }
    
    /**
    *   出荷番号記号から出荷番号最大値取得
    *
    *   @param string $syukka_key 出荷番号記号
    *   @return string 出荷番号
    */
    public function getMaxNoSyukka($syukka_key)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (is_null($stmt)) {
            $sql = "
                SELECT MAX(kb_kensa) AS kb_kensa 
                FROM {$this->schema} 
                WHERE kb_kensa LIKE :key 
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $key = "{$syukka_key}%";
        $stmt->bindParam(':key', $key, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return (is_null($result['kb_kensa'])) ?
            "{$syukka_key}000" : $result['kb_kensa'];
    }
}
