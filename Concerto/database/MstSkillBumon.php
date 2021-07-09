<?php

/**
*   mst_skill_bumon
*
*   @version 200331
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
    *   @return string ID
    */
    public function getMaxNo()
    {
        $sql = "
            SELECT MAX(cd_bumon) AS cd_bumon 
            FROM {$this->name} 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if (empty($result)) {
            return 'X0001';
        }

        $new_no = (int)mb_substr($result, 1) + 1;
        return sprintf('X%04d', $new_no);
    }
}
