<?php

/**
*   keiji_inf
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class KeijiInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_keiji' => parent::INTEGER,
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'nm_comment' => parent::STRING,
        'dt_kigen' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'cd_tanto' => parent::STRING,
    ];

    /**
    *   Validate
    *
    */
    public function isValidNo_doc($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNm_comment($val)
    {
        return Validate::isText($val, 0);
    }

    public function isValidDt_kigen($val)
    {
        if (is_null($val) || $val == '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidCd_bumon($val)
    {
        if ($val == null || $val == '') {
            return true;
        }
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto($val)
    {
        if ($val == null || $val == '') {
            return true;
        }
        return Validate::isTanto($val);
    }
}
