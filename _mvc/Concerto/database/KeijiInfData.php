<?php

/**
*   keiji_inf
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
class KeijiInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_keiji' => parent::INTEGER,
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'nm_comment' => parent::STRING,
        'dt_kigen' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'cd_tanto' => parent::STRING,
    ];

    public function isValidNo_doc(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_comment(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }

    public function isValidDt_kigen(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        if ($val === null || $val === '') {
            return true;
        }
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        if ($val === null || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }
}
