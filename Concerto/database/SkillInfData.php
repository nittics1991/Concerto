<?php

/**
*   skill_inf
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class SkillInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'kb_nendo' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_skill' => parent::STRING,
        'no_keiken' => parent::FLOAT,
        'no_level_r' => parent::INTEGER,
        'no_level_t' => parent::INTEGER,
        'dt_target_r' => parent::STRING,
        'dt_target_t' => parent::STRING,
        'no_point_p' => parent::INTEGER,
        'no_point_r' => parent::INTEGER,
        'nm_biko' => parent::STRING,
    ];

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidCd_skill($val)
    {
        return Validate::isTextInt($val, 1) && mb_ereg_match('\A\d{10}\z', $val);
    }
    public function isValidNo_keiken($val)
    {
        return Validate::isFloat($val, 0);
    }

    public function isValidNo_level_r($val)
    {
        return Validate::isTextInt($val, 0, 5);
    }

    public function isValidNo_level_t($val)
    {
        return Validate::isTextInt($val, 0, 5);
    }

    public function isValidDt_target_r($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_target_t($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidNo_point_p($val)
    {
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidNo_point_r($val)
    {
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidNm_biko($val)
    {
        return Validate::isText($val);
    }
}
