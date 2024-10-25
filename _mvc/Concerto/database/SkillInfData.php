<?php

/**
*   skill_inf
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
class SkillInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'kb_nendo' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_skill' => parent::STRING,
        'no_keiken' => parent::INTEGER,
        'no_level_r' => parent::INTEGER,
        'no_level_t' => parent::INTEGER,
        'dt_target_r' => parent::STRING,
        'dt_target_t' => parent::STRING,
        'no_point_p' => parent::INTEGER,
        'no_point_r' => parent::INTEGER,
        'nm_biko' => parent::STRING,
    ];

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

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidCd_skill(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 1) &&
            mb_ereg_match('\A\d{10}\z', $val);
    }
    public function isValidNo_keiken(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_level_r(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 5);
    }

    public function isValidNo_level_t(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 5);
    }

    public function isValidDt_target_r(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_target_t(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidNo_point_p(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 2);
    }

    public function isValidNo_point_r(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 2);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
