<?php

/**
*   mst_skill_bumon
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class MstSkillBumon extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mst_skill_bumon';

    /**
    *   採番
    *
    *   @return string
    */
    public function getMaxNo(): string
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

        /** @var string $result */
        $new_no = (int)mb_substr($result, 1) + 1;
        return sprintf('X%04d', $new_no);
    }
}
