<?php

/**
*   mst_skill_tanto
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class MstSkillTanto extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mst_skill_tanto';

    /**
    *   採番
    *
    *   @return string
    */
    public function getMaxNo(): string
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
