<?php

/**
*   mst_mitumori_bunya
*
*   @version 221226
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MstMitumoriBunyaData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'id_mitumori_bunya' => parent::INTEGER,
        'nm_bunya' => parent::STRING,
        'cd_system' => parent::STRING,
    ];

    public function isValidId_mitumori_bunya(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_bunya(
        mixed $val
    ): bool {
        return Validate::isText($val, 2) &&
            mb_ereg_match('\A[0-9]+.+\z', strval($val));
    }

    public function isValidCd_system(
        mixed $val
    ): bool {
        return Validate::isCdSystem($val);
    }
}
