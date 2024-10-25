<?php

/**
*   mst_bumon
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
class MstBumonData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_bumon' => parent::STRING,
        'nm_bumon' => parent::STRING,
        'fg_hatuban' => parent::STRING,
        'fg_cost' => parent::STRING,
    ];

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A[A-Z0-9]{5}\z', $val);
    }

    public function isValidNm_bumon(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidFg_hatuban(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidFg_cost(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }
}
