<?php

/**
*   wf_doc
*
*   @version 151105
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfDocData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array(
         "no_cyu" => parent::STRING
        , "no_seq" => parent::INTEGER
        , "nm_file" => parent::STRING
        , "no_doc" => parent::STRING
        , "nm_file_inf" => parent::STRING
        , "no_page" => parent::INTEGER
    );
    
    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = array();
    
    public function isValidNo_cyu($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isCyuban($val);
    }
    
    public function isValidNo_seq($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isInt($val, 0);
    }
    
    //nm_file
    
    public function isValidNo_doc($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }
    
    //nm_file_inf
    
    public function isValidNo_page($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0);
    }
}
