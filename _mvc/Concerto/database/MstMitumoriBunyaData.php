<?php

/**
*   mst_mitumori_bunya
*
*   @version 200326
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstMitumoriBunyaData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'id_mitumori_bunya' => parent::INTEGER
        , 'nm_bunya' => parent::STRING
        ,'cd_system' => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidId_mitumori_bunya($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_bunya($val)
    {
        return Validate::isText($val, 2) &&
            mb_ereg_match('\A[0-9]+.+\z', $val);
    }

    public function isValidCd_system($val)
    {
        return Validate::isCdSystem($val);
    }
}
