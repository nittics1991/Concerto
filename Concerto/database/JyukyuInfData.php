<?php

/**
*   jyukyu_inf
*
*   @version 210119
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class JyukyuInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_jyukyu' => parent::STRING,
        'no_cyu' => parent::STRING,
        'nm_syohin' => parent::STRING,
        'nm_model' => parent::STRING,
        'dt_pjyukyu' => parent::STRING,
        'dt_rjyukyu' => parent::STRING,
        'dt_syukka' => parent::STRING,
        'cd_jyukyu_tanto' => parent::STRING,
        'nm_biko' => parent::STRING,
        'no_psuryo' => parent::INTEGER,
        'no_rsuryo' => parent::INTEGER,
    ];

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNo_jyutyu($val)
    {
        return mb_ereg_match('\AJ[A-Z]{3}\d{5}\z', $val);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNm_syohin($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_model($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_pjyukyu($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_rjyukyu($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidDt_syukka($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidCd_ukeire_tanto($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidNm_biko($val)
    {
        return Validate::isText($val);
    }

    public function isValidNo_psuryo($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_rsuryo($val)
    {
        return Validate::isInt($val, 0);
    }
}
