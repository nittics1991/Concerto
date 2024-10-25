<?php

/**
*   mst_mitumori
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
class MstMitumoriData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_code' => parent::STRING,
        'nm_code' => parent::STRING,
        'cd_bumon' => parent::STRING,
    ];

    public function isValidCd_code(
        mixed $val
    ): bool {
        return Validate::isText($val, 1, 3);
    }

    public function isValidNm_code(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }
}
