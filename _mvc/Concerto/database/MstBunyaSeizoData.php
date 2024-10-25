<?php

/**
*   mst_bunya_seizo
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
class MstBunyaSeizoData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_bunya' => parent::INTEGER,
        'nm_bunya' => parent::STRING,
        'no_order' => parent::INTEGER,
        'dt_delete' => parent::STRING,
        'cd_system' => parent::STRING,
    ];

    public function isValidNo_bunya(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_bunya(
        mixed $val
    ): bool {
        return Validate::isText($val, 1, 100) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextSymbole($val);
    }

    public function isValidNo_order(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 999);
    }

    public function isValidDt_delete(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_system(
        mixed $val
    ): bool {
        return Validate::isCdSystem($val);
    }
}
