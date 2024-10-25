<?php

/**
*   login_inf
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
class LoginInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'ins_date' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'remote_addr' => parent::STRING,
    ];

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_tanto(
        mixed $val
    ): bool {
        return Validate::isText($val, null, 10);
    }

    public function isValidRemoteAddr(
        mixed $val
    ): bool {
        return Validate::isIpv4($val) ||
            Validate::isIpv6($val);
    }
}
