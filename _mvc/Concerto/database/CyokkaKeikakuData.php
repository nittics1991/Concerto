<?php

/**
*   cyokka_keikaku
*
*   @version 180719
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class CyokkaKeikakuData extends ModelData
{
    /**
    *   定時間勤務時間
    *
    *   @var float
    */
    public const TEIJIKAN = 7.75;

    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "kb_nendo" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "su_cyokka" => parent::INTEGER
        , "ri_cyokka" => parent::INTEGER
        , "yn_tanka" => parent::INTEGER
        , "tm_zangyo_m" => parent::DOUBLE
        , "ri_syukkin" => parent::INTEGER
    ];

    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }

    //cd_bumon_dmy

    public function isValidSu_cyokka($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidRi_cyokka($val)
    {
        return Validate::isInt($val, 0, 100);
    }

    public function isValidYn_tanka($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidTm_zangyo_m($val)
    {
        return Validate::isDouble($val, 0);
    }

    public function isValidRi_syukkin($val)
    {
        return Validate::isInt($val, 0, 100);
    }
}
