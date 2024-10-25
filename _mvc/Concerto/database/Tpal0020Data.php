<?php

/**
*   tpal0020
*
*   @version 240515
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;

/**
*   @extends ModelData<string|int>
*/
class Tpal0020Data extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'chuban' => parent::STRING,
        'koban' => parent::STRING,
        'gb_cd' => parent::STRING,
        'hinmei' => parent::STRING,
        'up_day' => parent::STRING,
        'gouka' => parent::INTEGER,
        'kanse_pday' => parent::STRING,
        'kanse_day' => parent::STRING,
        'kan_flg' => parent::STRING,
        'noki_pday' => parent::STRING,
        'ins_day' => parent::STRING,
    ];
}
