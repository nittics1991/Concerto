<?php

/**
*   tsal0050
*
*   @version 180509
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class Tsal0050Data extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'irai_no' => parent::STRING
        , 'noki_day' => parent::STRING
        , 'uke_day' => parent::STRING
        , 'ko_no' => parent::STRING
        , 'chuban' => parent::STRING
        , 'koban' => parent::STRING
        , 'up_day' => parent::STRING
        , 'h_tehai_cd' => parent::STRING
        , 'h_hinmei' => parent::STRING
        , 'tori_name' => parent::STRING
        , 'suryo' => parent::INTEGER
        , 'uni_cd' => parent::STRING
        , 'money' => parent::INTEGER
        , 'ken_day' => parent::STRING
        , 'gb_cd' => parent::STRING
        , 'kan_day' => parent::STRING
        , 'chu_day' => parent::STRING
        , 'eda_no' => parent::STRING
        , 'ken_money' => parent::INTEGER
        , 'ken_suryo' => parent::INTEGER
        , 'tanka' => parent::INTEGER
    ];
}
