<?php

/**
*   kunmu_inf
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class KinmuInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'up_date' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'dt_kinmu' => parent::STRING,
        'tm_kinmu' => parent::FLOAT,
    ];

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidDt_kinmu($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidTm_kinmu($val)
    {
        return Validate::isTextFloat($val, -300, 300) ||
            Validate::isTextInt($val, -300, 300);
    }
}
