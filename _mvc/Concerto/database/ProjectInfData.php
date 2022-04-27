<?php

/**
*   project_inf
*
*   @version 210119
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ProjectInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_project' => parent::INTEGER,
        'nm_project' => parent::STRING,
        'dt_pkansei' => parent::STRING,
        'fg_kansei' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_system' => parent::STRING,
    ];

    public function isValidNo_project($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidNm_project($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_pkansei($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidFgKansei($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidCd_system($val)
    {
        return Validate::isCdSystem($val);
    }
}
