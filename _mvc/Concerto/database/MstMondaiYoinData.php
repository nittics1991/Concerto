<?php

/**
*   mst_mondai_yoin
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstMondaiYoinData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_bunrui1' => parent::INTEGER
        , 'no_bunrui2' => parent::INTEGER
        , 'cd_yoin' => parent::STRING
        , 'nm_yoin' => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [
    ];

    public function isValidNo_bunrui1($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidNo_bunrui2($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidCd_yoin($val)
    {
        return Validate::isText($val, 2, 3) &&
            mb_ereg_match('[A-Z0-9]{2,3}', $val);
    }

    public function isValidNm_yoin($val)
    {
        return Validate::isText($val);
    }
}
