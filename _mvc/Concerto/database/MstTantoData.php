<?php

/**
*   mst_tanto
*
*   @version 210915
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\MstBumonData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class MstTantoData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_tanto' => parent::STRING,
        'disp_seq' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'mail_add' => parent::STRING,
        'kengen' => parent::STRING,
        'kengen_db' => parent::STRING,
        'kengen_sm' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'password' => parent::STRING,
        'fg_mail' => parent::STRING,
        'ri_cyokka' => parent::INTEGER,
        'username' => parent::STRING,
        'fg_cookie' => parent::STRING,
        'cd_hash' => parent::STRING,
        'dt_hash' => parent::STRING,
        'dt_delete' => parent::STRING,
        'kengen_login' => parent::STRING,
    ];

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidDisp_seq($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_tanto($val)
    {
        return Validate::isText($val);
    }

    public function isValidMail_add($val)
    {
        return Validate::isEmail($val);
    }

    public function isValidKengen($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidKengen_db($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidKengen_sm($val)
    {
        return Validate::isTextInt($val, 0, 3);
    }

    public function isValidCd_bumon($val)
    {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidPassword($val)
    {
        return Validate::isAscii($val, 5);
    }

    public function isValidFg_mail($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidRi_cyokka($val)
    {
        return Validate::isInt($val, 0, 100);
    }

    public function isValidUsername($val)
    {
        return Validate::isUser($val);
    }

    public function isValidFg_cookie($val)
    {
        return Validate::isText($val);
    }

    public function isValidCd_hash($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_hash($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_delete($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidKengen_login($val)
    {
        return Validate::isTextBool($val);
    }
}
