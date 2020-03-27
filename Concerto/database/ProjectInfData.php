<?php

/**
*   project_inf
*
*   @version 150428
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ProjectInfData extends ModelData
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
        , "nm_project" => parent::STRING
        , "dt_pkansei" => parent::STRING
        , "fg_kansei" => parent::STRING
        , "cd_tanto" => parent::STRING
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
    
    public function isValidNm_project($val)
    {
        return Validate::isText($val);
    }
    
    public function isValidDt_pkansei($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }
    
    public function isValidFgKansei($val)
    {
        return Validate::isTextBool($val);
    }
    
    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }
}
