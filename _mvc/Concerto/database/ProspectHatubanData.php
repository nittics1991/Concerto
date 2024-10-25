<?php

/**
*   prospect_hatuban
*
*   @version 230920
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class ProspectHatubanData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'kb_nendo' => parent::STRING,
        'no_seq' => parent::INTEGER,
    ];

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }
}
