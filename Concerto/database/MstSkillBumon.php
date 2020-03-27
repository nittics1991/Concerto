<?php

/**
*   mst_skill_bumon
*
*   @version 160705
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use InvalidArgumentException;
use RuntimeException;
use Concerto\standard\ModelDb;

class MstSkillBumon extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_skill_bumon';
    
    /**
    *   採番
    *
    *   @param string $jigyoubu 事業部コード S:（ＳＩジ） E:(電機ジ) T:(検Sジ)
    *   @param string $sbu BUコード
    *   @return string ID
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function createID($jigyoubu, $sbu)
    {
        /**
        *   プリペア
        *
        *   @var resorce
        */
        static $stmt;
        
        if (!mb_ereg_match('\A[A-Z]\z', $jigyoubu) || !mb_ereg_match('\A[A-Z]\z', $sbu)) {
            throw new InvalidArgumentException("data type error");
        }
        
        if (is_null($stmt)) {
            $sql = "
                SELECT MAX(cd_bumon) AS cd_bumon 
                FROM {$this->name} 
                WHERE cd_bumon LIKE :bumon
            ";
            
            $stmt = $this->pdo->prepare($sql);
        }
        
        $bumon = "X{$jigyoubu}{$sbu}%";
        
        $stmt->bindParam(':bumon', $bumon, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if (is_null($result[0]['cd_bumon'])) {
            return "X{$jigyoubu}{$sbu}01";
        } else {
            $cd_bumon = $result[0]['cd_bumon'];
            $code = mb_substr($cd_bumon, 0, 3);
            $no = mb_substr($cd_bumon, 3, 2);
            
            if ($no >= 99) {
                throw new RuntimeException("no overflow");
            }
            
            $new_bumon = sprintf("%s%02d", $code, ++$no);
            return $new_bumon;
        }
    }
}
