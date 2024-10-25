<?php

/**
*   tsal0010
*
*   @version 230208
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;

/**
*   @extends ModelData<string|int>
*/
class Tsal0010Data extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'irai_no' => parent::STRING,
        'jugyoin_no' => parent::STRING,
        'irai_day' => parent::STRING,
        'mirai_day' => parent::STRING,
        'noki_day' => parent::STRING,
        'nbasyo_zip' => parent::STRING,
        'nbasyo_add' => parent::STRING,
        'nbasyo_tel' => parent::STRING,
        'nbasyo_tan' => parent::STRING,
        'up_day' => parent::STRING,
        'suryo' => parent::INTEGER,
        'chuban' => parent::STRING,
        'kouban' => parent::STRING,
        'gb_cd' => parent::STRING,
        'skei_tan' => parent::STRING,
    ];
}
