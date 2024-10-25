<?php

/**
*   kunmu_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|float>
*/
class KinmuInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'dt_kinmu' => parent::STRING,
        'tm_kinmu' => parent::FLOAT,
    ];

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidDt_kinmu(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidTm_kinmu(
        mixed $val
    ): bool {
        return Validate::isTextFloat($val, -300, 300) ||
            Validate::isTextInt($val, -300, 300);
    }
}
