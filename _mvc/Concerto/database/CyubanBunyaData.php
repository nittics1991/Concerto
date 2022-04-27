<?php

/**
*   cyuban_bunya
*
*   @version 210119
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
        'no_cyu' => parent::STRING,
        'no_bunya' => parent::INTEGER,
    ];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_bunya($val)
    {
        return Validate::isInt($val, 0);
    }
}
