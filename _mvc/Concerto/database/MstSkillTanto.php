<?php

/**
*   mst_skill_tanto
*
*   @version 200331
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use RuntimeException;
use Concerto\standard\ModelDb;

class MstSkillTanto extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_skill_tanto';

    /**
    *   採番
    *
    *   @return string ID
    */
    public function getMaxNo()
    {
        $sql = "
            SELECT MAX(cd_tanto) AS cd_tanto 
            FROM {$this->name} 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = (string)$stmt->fetchColumn();

        if (empty($result)) {
            return "80001ITC";
        }

        $new_no = (int)mb_substr($result, 0, 5) + 1;
        return sprintf('%05dITC', $new_no);
    }
}
