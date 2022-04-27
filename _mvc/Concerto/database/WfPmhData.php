<?php

/**
*   wf_pmh
*
*   @version 210115
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfPmhData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'cd_tanto' => parent::STRING,
        'dt_kakunin' => parent::STRING,
    ];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidDt_kakunin($val)
    {
        return Validate::isTextDate($val);
    }
}
