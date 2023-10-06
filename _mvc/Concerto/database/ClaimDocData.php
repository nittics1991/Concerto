<?php

/**
*   claim_doc
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\ClaimInfData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class ClaimDocData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_claim' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_file' => parent::STRING,
        'nm_file_inf' => parent::STRING,
    ];

    public function isValidNo_claim(
        mixed $val
    ): bool {
        return ClaimInfData::isValidNo_claim($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0);
    }

    public function isValidNm_file(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }

    public function isValidNm_file_inf(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }
}
