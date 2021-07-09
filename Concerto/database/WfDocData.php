<?php

/**
*   wf_doc
*
*   @version 201130
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class WfDocData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
         'no_cyu' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_file' => parent::STRING,
        'nm_file_inf' => parent::STRING,
        'no_page' => parent::INTEGER,
        'cd_job' => parent::STRING,
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    /**
    *   no_seq => cd_job
    *
    *   @param int $no_seq
    *   @return string
    */
    public function noSeq2CdJob(
        int $no_seq
    ): string {
        return sprintf('wf_doc_%02d', $no_seq);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_job($val)
    {
        return mb_ereg_match('\Awf_doc_[0-9]{2}\z', $val);
    }

    public function isValidNm_file($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_file_inf($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isText($val);
    }
}
