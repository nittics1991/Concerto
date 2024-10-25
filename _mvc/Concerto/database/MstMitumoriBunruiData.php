<?php

/**
*   mst_mitumori_bunrui
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
class MstMitumoriBunruiData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'id_mitumori_bunrui' => parent::INTEGER,
        'nm_bunrui' => parent::STRING,
        'cd_system' => parent::STRING,
    ];

    public function isValidId_mitumori_bunrui(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_bunrui(
        mixed $val
    ): bool {
        return Validate::isText($val, 1);
    }

    public function isValidCd_system(
        mixed $val
    ): bool {
        return Validate::isCdSystem($val);
    }
}
