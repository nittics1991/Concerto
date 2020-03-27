<?php

/**
*   setubi_yoyaku
*
*   @version 180124
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class SetubiYoyakuData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_setubi' => parent::STRING
        , 'cd_tanto' => parent::STRING
        , 's_date' => parent::STRING
        , 'e_date' => parent::STRING
        , 'nm_biko' => parent::STRING
        , 'no_set' => parent::STRING
        , 'up_date' => parent::STRING
        , 'cd_rev' => parent::STRING
    ];
    
    public function isValidCd_setubi($val)
    {
        return mb_ereg_match('\A[0-9]{4}\z', $val);
    }
    
    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isValidS_date($val)
    {
        return Validate::isTextDateTime($val);
    }
    
    public function isValidE_date($val)
    {
        return Validate::isTextDateTime($val);
    }
    
    public function isValidNm_biko($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }
    
    public function isValidUp_date($val)
    {
        return Validate::isTextDateTime($val);
    }
    
    public function isValidCd_rev($val)
    {
        return Validate::isTanto($val);
    }
}
