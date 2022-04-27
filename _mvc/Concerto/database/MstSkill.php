<?php

/**
*   mst_skill
*
*   @version 160822
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDbTree;

class MstSkill extends ModelDbTree
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_skill';

    /**
    *   ルートノード親値
    *
    *   @var mixed
    */
    protected $root = '';

    /**
    *   primary key名(overwrite)
    *
    *   @var string
    */
    protected $primarykey = 'cd_skill';

    /**
    *   親カラム名
    *
    *   @var string
    */
    protected $parent = 'cd_parent';

    /**
    *   cd_skill最大値取得
    *
    *   @param string $cd_parent 親ID
    *   @return string
    */
    public function getMaxNoSkill($cd_parent)
    {
        $mstSkillData = new MstSkillData();
        $obj = clone $mstSkillData;
        $obj->cd_skill = $cd_parent;
        $children = $this->children($obj, 'cd_skill DESC');

        $obj2 = clone $mstSkillData;

        if (empty($children)) {
            $obj2->cd_skill = $cd_parent;
            return $obj2->incrementBunrui(true);
        } else {
            $obj2->cd_skill = $children[0]['cd_skill'];
            return $obj2->incrementBunrui();
        }
    }

    /**
    *   マトリクス表
    *
    *   @param string|null $cd_parent 親ID
    *   @return array
    */
    public function getDimensionsChildren($cd_parent = null)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                WITH RECURSIVE 
                    tmp AS (
                        SELECT * 
                            , ARRAY[cd_skill] AS path 
                            , 1 AS depth 
                        FROM public.mst_skill 
                        WHERE cd_parent = :id 
                        UNION 
                        SELECT B.* 
                            , path || B.cd_skill AS path 
                            , depth + 1 AS depth 
                        FROM tmp A 
                        JOIN public.mst_skill B 
                            ON B.cd_parent = A.cd_skill 
                                AND NOT B.cd_skill = ANY(path) 
                    )
                SELECT cd_skill, nm_skill, dt_yukou 
                    , D.path[1] AS cd_skill1 
                    , D.path[2] AS cd_skill2 
                    , D.path[3] AS cd_skill3 
                    , D.path[4] AS cd_skill4 
                    , D.path[5] AS cd_skill5 
                    , (SELECT nm_skill FROM tmp E WHERE E.cd_skill = D.path[1]) AS nm_skill1 
                    , (SELECT nm_skill FROM tmp E WHERE E.cd_skill = D.path[2]) AS nm_skill2 
                    , (SELECT nm_skill FROM tmp E WHERE E.cd_skill = D.path[3]) AS nm_skill3 
                    , (SELECT nm_skill FROM tmp E WHERE E.cd_skill = D.path[4]) AS nm_skill4 
                    , (SELECT nm_skill FROM tmp E WHERE E.cd_skill = D.path[5]) AS nm_skill5 
                FROM tmp D 
                ORDER BY cd_skill 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':id', $cd_parent, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
