<?php

/**
*   wf_free
*
*   @version 201117
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfFreeData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_cyu' => parent::STRING
        , 'no_page' => parent::INTEGER
        , 'no_seq' => parent::INTEGER
        , 'cd_elem' => parent::STRING
        , 'cd_plan' => parent::STRING
        , 'cd_real' => parent::STRING
        , 'cd_job' => parent::STRING
        , 'nm_job' => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_rev($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_elem($val)
    {
        return Validate::isText($val);
    }

    //cd_plan
    //cd_real

    public function isValidCd_job($val)
    {
        return Validate::isText($val, 0);
    }

    public function isValidNm_job($val)
    {
        return Validate::isText($val);
    }
}
