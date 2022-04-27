<?php

/**
*   mst_mitumori
*
*   @version 180530
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstMitumoriData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_code' => parent::STRING,
        'nm_code' => parent::STRING,
        'cd_bumon' => parent::STRING,
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidCd_code($val)
    {
        return Validate::isText($val, 1, 3);
    }

    public function isValidNm_code($val)
    {
        return Validate::isText($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }
}
