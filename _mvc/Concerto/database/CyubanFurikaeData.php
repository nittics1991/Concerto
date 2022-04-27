<?php

/**
*   cyuban_furikae
*
*   @version 180525
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class CyubanFurikaeData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "no_cyu" => parent::STRING
        , "no_mitumori" => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_mitumori($val)
    {
        return Validate::isMitumoriNo($val);
    }
}
