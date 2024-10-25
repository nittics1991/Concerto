<?php

/**
*   tmal0160
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string>
*/
class Tmal0160Data extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'kisyu_cd' => parent::STRING,
        'kisyu_name' => parent::STRING,
        'up_day' => parent::STRING,
    ];

    public function isValidKisyu_cd(
        mixed $val
    ): bool {
        return Validate::isText($val, 2, 2);
    }

    public function isValidKisyu_name(
        mixed $val
    ): bool {
        return Validate::isText($val, 1);
    }

    public function isValidUp_day(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }
}
