<?php

/**
*   mst_mitumori_bunrui
*
*   @version 200326
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstMitumoriBunruiData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'id_mitumori_bunrui' => parent::INTEGER
        ,'nm_bunrui' => parent::STRING
        ,'cd_system' => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidId_mitumori_bunrui($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_bunrui($val)
    {
        return Validate::isText($val, 1);
    }

    public function isValidCd_system($val)
    {
        return Validate::isCdSystem($val);
    }
}
