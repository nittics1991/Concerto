<?php

/**
*   tsal0010
*
*   @version 180509
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class Tsal0010Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'irai_no' => parent::STRING
        , 'jugyoin_no' => parent::STRING
        , 'irai_day' => parent::STRING
        , 'mirai_day' => parent::STRING
        , 'noki_day' => parent::STRING
        , 'nbasyo_zip' => parent::STRING
        , 'nbasyo_add' => parent::STRING
        , 'nbasyo_tel' => parent::STRING
        , 'nbasyo_tan' => parent::STRING
        , 'up_day' => parent::STRING
        , 'suryo' => parent::INTEGER
        , 'chuban' => parent::STRING
        , 'kouban' => parent::STRING
        , 'gb_cd' => parent::STRING
    ];
}
