<?php

/**
*   wf_kennann
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfKennannData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'no_seq' => parent::INTEGER,
        'cd_type' => parent::STRING,
        'nm_kennann' => parent::STRING,
        'dt_kigen' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'nm_taisaku' => parent::STRING,
        'dt_kakunin' => parent::STRING,
        'nm_kakunin' => parent::STRING,
        'fg_kennann' => parent::STRING,
        'cd_tanto_kanri' => parent::STRING,
        'nm_type' => parent::STRING,
    ];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page($val)
    {
        return Validate::isTextInt($val, 0);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isTextInt($val, 0);
    }

    public function isValidCd_type($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_kennann($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_kigen($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidNm_tanto($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_taisaku($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidDt_kakunin($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidNm_kakunin($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidFg_kennann($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidCd_tanto_kanri($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNm_type($val)
    {
        return Validate::isText($val);
    }
}
