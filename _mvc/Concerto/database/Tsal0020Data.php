<?php

/**
*   tsal0020
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;

/**
*   @extends ModelData<string|int>
*/
class Tsal0020Data extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'irai_no' => parent::STRING,
        'kkiji' => parent::STRING,
        'tehai_cd' => parent::STRING,
        'up_day' => parent::STRING,
        'ren_no' => parent::STRING,
        'suryo' => parent::INTEGER,
        'noki_pday' => parent::STRING,
    ];
}
