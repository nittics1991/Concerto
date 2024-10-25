<?php

/**
*   cyunyu_lock
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
class CyunyuLockData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_cyu' => parent::STRING,
        'no_ko' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'ins_date' => parent::STRING,
    ];

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_ko(
        mixed $val
    ): bool {
        return Validate::isKoban($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }
}
