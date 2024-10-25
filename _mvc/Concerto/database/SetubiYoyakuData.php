<?php

/**
*   setubi_yoyaku
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
class SetubiYoyakuData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_setubi' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'dt_start' => parent::STRING,
        'dt_end' => parent::STRING,
        'nm_biko' => parent::STRING,
        'up_date' => parent::STRING,
    ];

    public function isValidCd_setubi(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match('\A[0-9]{4}\z', $val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidDt_start(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidDt_end(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }
}
