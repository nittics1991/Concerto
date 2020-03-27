<?php

/**
*   hatuban_hattyu
*
*   @version 180523
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class HatubanHattyuData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'update' => parent::STRING,
        'editor' => parent::STRING,
        'no_mitumori' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_phattyu' => parent::INTEGER,
        'dt_phattyu' => parent::STRING
    ];
    
    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];
    
    public function isValidUpdate($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isValidNo_mitumori($val)
    {
        return Validate::isMitumoriNo($val);
    }
    
    public function isValidNo_cyu($val)
    {
        if (empty($val)) {
            return true;
        }
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_phattyu($val)
    {
        return Validate::isInt($val, 0);
    }
    
    public function isValidDt_phattyu($val)
    {
        return Validate::isTextDate($val);
    }
}
