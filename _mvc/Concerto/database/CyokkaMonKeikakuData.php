<?php

/**
*   cyokka_mon_keikaku
*
*   @version 180723
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class CyokkaMonKeikakuData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "kb_nendo" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "dt_yyyymm" => parent::STRING
        , "dt_kado" => parent::FLOAT
        , "tm_zitudo" => parent::DOUBLE
        , "tm_teizikan" => parent::DOUBLE
        , "tm_zangyo" => parent::DOUBLE
        , "tm_cyokka" => parent::DOUBLE
        , "tm_zitudo_m" => parent::DOUBLE
        , "tm_teizikan_m" => parent::DOUBLE
        , "tm_zangyo_m" => parent::DOUBLE
        , "tm_cyokka_m" => parent::DOUBLE
        , "tm_hoyu_cyokka" => parent::INTEGER
        , "yn_yosan" => parent::INTEGER
        , "yn_soneki" => parent::INTEGER
    ];

    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidDt_yyyymm($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_kado($val)
    {
        return Validate::isInt($val, 0, 31) || Validate::isFloat($val, 0, 31);
    }

    public function isValidTm_zitudo($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_teizikan($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_zangyo($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_cyokka($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_zitudo_m($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_teizikan_m($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_zangyo_m($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_cyokka_m($val)
    {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_hoyu_cyokka($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_yosan($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_soneki($val)
    {
        return Validate::isInt($val, 0);
    }
}
