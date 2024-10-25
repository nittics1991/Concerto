<?php

/**
*   mst_mondai_bunrui1
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
class MstMondaiBunrui1Data extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_bunrui' => parent::INTEGER,
        'nm_bunrui' => parent::STRING,
    ];

    public function isValidNo_bunrui(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidNm_bunrui(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
