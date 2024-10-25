<?php

/**
*   mitumori_doc
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MitumoriDocData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_mitumori' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_file' => parent::STRING,
        'nm_file_inf' => parent::STRING,
    ];

    public function isValidNo_mitumori(
        mixed $val
    ): bool {
        return Validate::isMitumoriNo($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    //nm_file
    //nm_file_inf
}
