<?php

/**
*   mst_mondai_yoin
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
class MstMondaiYoinData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_bunrui1' => parent::INTEGER,
        'no_bunrui2' => parent::INTEGER,
        'cd_yoin' => parent::STRING,
        'nm_yoin' => parent::STRING,
    ];

    public function isValidNo_bunrui1(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidNo_bunrui2(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidCd_yoin(
        mixed $val
    ): bool {
        return Validate::isText($val, 2, 3) &&
            mb_ereg_match('[A-Z0-9]{2,3}', strval($val));
    }

    public function isValidNm_yoin(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
