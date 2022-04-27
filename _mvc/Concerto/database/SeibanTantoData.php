<?php

/**
*   seiban_tanto
*
*   @version 200901
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class SeibanTantoData extends ModelData
{
    /**
    *   no_seq 手動設定
    *
    *   @var string
    */
    public const MANUAL = 'M';

    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "ins_date" => parent::STRING
        , "no_cyu" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "no_seq" => parent::STRING
        , "no_ko" => parent::STRING
    ];

    public function isValidIns_date($val)
    {
        return Validate::isTextDateTime($val);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNo_Seq($val)
    {
        if ($val == 'M') {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }

    public function isValidNo_ko($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isKoban($val);
    }
}
