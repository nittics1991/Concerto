<?php

/**
*   tpal0220
*
*   @version 180509
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class Tpal0220Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'chuban' => parent::STRING
        , 'koban' => parent::STRING
        , 'gb_cd' => parent::STRING
        , 'himoku_cd' => parent::STRING
        , 'chumon_no' => parent::STRING
        , 'kanjo_ym' => parent::STRING
        , 'chunyu_day' => parent::STRING
        , 'up_day' => parent::STRING
        , 'chunyu_mny' => parent::INTEGER
        , 'hinmei' => parent::STRING
        , 'torihiki' => parent::STRING
        , 'suryo' => parent::INTEGER
        , 'furi_cd' => parent::STRING
        , 'furi_seiban' => parent::STRING
    ];
}
