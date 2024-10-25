<?php

/**
*   project_inf
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
class ProjectInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_project' => parent::INTEGER,
        'nm_project' => parent::STRING,
        'dt_pkansei' => parent::STRING,
        'fg_kansei' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_system' => parent::STRING,
    ];

    public function isValidNo_project(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidNm_project(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_pkansei(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidFgKansei(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidCd_system(
        mixed $val
    ): bool {
        return Validate::isCdSystem($val);
    }
}
