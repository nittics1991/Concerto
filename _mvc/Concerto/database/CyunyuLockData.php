<?php

/**
*   cyunyu_lock
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class CyunyuLockData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "no_cyu" => parent::STRING
        , "no_ko" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "ins_date" => parent::STRING
    ];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_ko($val)
    {
        return Validate::isKoban($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidIns_date($val)
    {
        return Validate::isTextDateTime($val);
    }
}
