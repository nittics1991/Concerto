<?php

/**
*   doc_inf
*
*   @version 160325
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class DocInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "ins_date" => parent::STRING
        , "cd_kbn_01" => parent::STRING
        , "cd_kbn_02" => parent::STRING
        , "nm_doc" => parent::STRING
        , "nm_doc_inf" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "nm_upfile_url" => parent::STRING
        , "nm_upfile_dir  " => parent::STRING
    ];

    public function isValidIns_date($val)
    {
        return mb_ereg_match('\A\d{4}/\d{2}/\d{2}_\d{2}:\d{2}:\d{2}(\.\d*)*\z', $val);
    }

    public function isValidCd_kbn_01($val)
    {
        if (!is_null($val)) {
            return Validate::isTextInt($val);
        }
        return true;
    }

    public function isValidCd_kbn_02($val)
    {
        if (!is_null($val)) {
            return Validate::isTextInt($val);
        }
        return true;
    }

    public function isValidNm_doc($val)
    {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }

    public function isValidNm_doc_inf($val)
    {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNm_upfile_url($val)
    {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }

    public function isValidNm_upfile_dir($val)
    {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }
}
