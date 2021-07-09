<?php

/**
*   tmal0160
*
*   @version 190910
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class Tmal0160Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "kisyu_cd" => parent::STRING
        , "kisyu_name" => parent::STRING
        , "up_day" => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidKisyu_cd($val)
    {
        return Validate::isText($val, 2, 2);
    }

    public function isValidKisyu_name($val)
    {
        return Validate::isText($val, 1);
    }

    public function isValidUp_day($val)
    {
        return Validate::isTextDateTime($val);
    }
}
