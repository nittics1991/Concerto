<?php

/**
*   tsal0020
*
*   @version 180509
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class Tsal0020Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'irai_no' => parent::STRING
        , 'kkiji' => parent::STRING
        , 'tehai_cd' => parent::STRING
        , 'up_day' => parent::STRING
        , 'ren_no' => parent::STRING
        , 'suryo' => parent::INTEGER
        , 'noki_pday' => parent::STRING
    ];
}
