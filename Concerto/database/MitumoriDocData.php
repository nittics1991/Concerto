<?php

/**
*   mitumori_doc
*
*   @version 180605
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MitumoriDocData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'update' => parent::STRING
        , 'editor' => parent::STRING
        , 'no_mitumori' => parent::STRING
        , 'no_seq' => parent::INTEGER
        , 'nm_file' => parent::STRING
        , 'nm_file_inf' => parent::STRING
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
    
    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }
    
    //nm_file
    //nm_file_inf
}
