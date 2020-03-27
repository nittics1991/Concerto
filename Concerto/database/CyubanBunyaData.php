<?php

/**
*   cyuban_bunya
*
*   @version 180403
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class CyubanBunyaData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'update' => parent::STRING,
        'editor' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_bunya' => parent::INTEGER,
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
    
    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_bunya($val)
    {
        return Validate::isInt($val, 0);
    }
}
