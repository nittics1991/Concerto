<?php

/**
*   project_cyuban
*
*   @version 150428
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ProjectCyubanData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array(
        "update" => parent::STRING
        , "editor" => parent::STRING
        , "no_project" => parent::INTEGER
        , "no_cyu" => parent::STRING
    );
    
    public function isValidUpdate($val)
    {
        return Validate::isTextDate($val);
    }
    
    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }
    
    public function isValidNo_project($val)
    {
        return Validate::isInt($val, 1);
    }
    
    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
}
