<?php

/**
*   wf_gijiroku
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfGijirokuData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
         'no_cyu' => parent::STRING,
         'no_page' => parent::INTEGER,
         'cd_type' => parent::STRING,
         'dt_kaisai' => parent::STRING,
         'cd_rank' => parent::STRING,
         'nm_basyo' => parent::STRING,
         'nm_syusseki' => parent::STRING,
         'nm_singi' => parent::STRING,
    ];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page($val)
    {
        return Validate::isTextInt($val, 0);
    }

    public function isValidCd_type($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_kaisai($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidCd_rank($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_basyo($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_syusseki($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_singi($val)
    {
        return Validate::isText($val);
    }
}
