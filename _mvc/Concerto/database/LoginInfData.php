<?php

/**
*   login_inf
*
*   @version 190904
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class LoginInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "ins_date" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "nm_tanto" => parent::STRING
        , "remote_addr" => parent::STRING
    ];

    /**
    *   Validate ins_date
    *
    *   @param mixed $val 判定値
    *   @return bool 結果
    */
    public function isValidIns_date($val)
    {
        return Validate::isTextDateTime($val);
    }

    /**
    *   Validate cd_tanto
    *
    *   @param mixed $val 判定値
    *   @return bool 結果
    */
    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    /**
    *   Validate nm_tanto
    *
    *   @param mixed $val 判定値
    *   @return bool 結果
    */
    public function isValidNm_tanto($val)
    {
        return Validate::isText($val, null, 10);
    }

    /**
    *   Validate remote_addr
    *
    *   @param mixed $val 判定値
    *   @return bool 結果
    */
    public function isValidRemoteAddr($val)
    {
        return Validate::isIpv4($val) || Validate::isIpv6($val);
    }
}
