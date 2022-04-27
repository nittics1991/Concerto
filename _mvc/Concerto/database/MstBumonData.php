<?php

/**
*   mst_bumon
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstBumonData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_bumon' => parent::STRING,
        'nm_bumon' => parent::STRING,
        'fg_hatuban' => parent::STRING,
        'fg_cost' => parent::STRING,
    ];

    public function isValidCd_bumon($val)
    {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A[A-Z0-9]{5}\z', $val);
    }

    public function isValidNm_bumon($val)
    {
        return Validate::isText($val);
    }

    public function isValidFg_hatuban($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidFg_cost($val)
    {
        return Validate::isTextBool($val);
    }
}
