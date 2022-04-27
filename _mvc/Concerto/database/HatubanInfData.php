<?php

/**
*   hatuban_inf
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class HatubanInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "no_cyu" => parent::STRING
        , "dt_hatuban" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "dt_kakunin" => parent::STRING
    ];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidDt_hatuban($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidDt_kakunin($val)
    {
        return Validate::isTextDate($val);
    }
}
