<?php

/**
*   mst_bunya_seizo
*
*   @version 200326
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstBunyaSeizoData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'update' => parent::STRING,
        'editor' => parent::STRING,
        'no_bunya' => parent::INTEGER,
        'nm_bunya' => parent::STRING,
        'no_order' => parent::INTEGER,
        'dt_delete' => parent::STRING
        ,'cd_system' => parent::STRING
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
    
    public function isValidNo_bunya($val)
    {
        return Validate::isInt($val, 0);
    }
    
    public function isValidNm_bunya($val)
    {
        return Validate::isText($val, 1, 100) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextSymbole($val);
    }
    
    public function isValidNo_order($val)
    {
        return Validate::isInt($val, 0, 999);
    }
    
    public function isValidDt_delete($val)
    {
        return Validate::isTextDateTime($val);
    }
    
    public function isValidCd_system($val)
    {
        return Validate::isCdSystem($val);
    }
}
