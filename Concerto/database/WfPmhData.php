<?php

/**
*   wf_pmh
*
*   @version 170522
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfPmhData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array(
        "update" => parent::STRING
        , "editor" => parent::STRING
        , "cd_syukka" => parent::STRING
    );
    
    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = array(
    );
    
    public function isValidUpdate($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isValidCd_syukka($val)
    {
        return mb_ereg_match('\A[A-Z](K|S)[0-9]{3}\z', $val);
    }
}
