<?php

/**
*   mst_bumon_hist
*
*   @version 200323
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstBumonHistData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_bumon' => parent::STRING,
        'nm_bumon' => parent::STRING,
        'dt_start' => parent::STRING,
        'dt_end' => parent::STRING,
        'cd_bumon_mst' => parent::STRING,
    ];
    
    public function isValidCd_bumon($val)
    {
        return is_string($val)
            && mb_check_encoding($val)
            && mb_ereg_match('\A[A-Z0-9]{5,6}\z', $val);
    }
    
    public function isValidNm_bumon($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidFg_kyoiku($val)
    {
        return Validate::isTextBool($val);
    }
    
    public function isValidDt_start($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidDt_end($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidCd_bumon_mst($val)
    {
        return is_string($val)
            && mb_check_encoding($val)
            && mb_ereg_match('\A[A-Z0-9]{5,6}\z', $val);
    }
}
