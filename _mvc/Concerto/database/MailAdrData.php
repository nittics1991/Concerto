<?php

/**
*   mail_adr
*
*   @version 151021
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MailAdrData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "update" => parent::STRING
        , "editor" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "cd_adr" => parent::STRING
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

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidCd_adr($val)
    {
        return Validate::isText($val);
    }
}
