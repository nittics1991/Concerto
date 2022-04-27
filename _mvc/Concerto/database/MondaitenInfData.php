<?php

/**
*   mondaiten_inf
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MondaitenInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "no_cyu" => parent::STRING
        , "no_page" => parent::INTEGER
        , "no_seq" => parent::INTEGER
        , "cd_hassei" => parent::STRING
        , "cd_bunrui" => parent::STRING
        , "no_mondai" => parent::INTEGER
        , "tm_cyokka" => parent::FLOAT
        , "dt_hassei" => parent::STRING
        , "dt_kaito" => parent::STRING
        , "dt_kakunin" => parent::STRING
        , "nm_biko" => parent::STRING
        , "up_date" => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    /**
    *   発生場所
    *
    *   @var array
    */
    private $cd_hassei_list = [
        0 => '工場設計',
        1 => '工場試験',
        2 => '工場その他',
        3 => '工場出荷',
        4 => '現調・試験',
        5 => '現地その他',
    ];

    /**
    *   発生場所取得
    *
    *   @param string|null $id  発生場所番号
    *   @return string|array|null
    */
    public function getHasseiName($id = null)
    {
        if (is_null($id)) {
            return $this->cd_hassei_list;
        }

        if (array_key_exists($id, $this->cd_hassei_list)) {
            return $this->cd_hassei_list[$id];
        }
        return null;
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

    public function isValidCd_hassei($val)
    {
        return Validate::isTextInt($val, 0, 5);
    }

    public function isValidCd_bunrui($val)
    {
        return Validate::isText($val, 2, 2) &&
            mb_ereg_match('\A[0-9A-Z]{2}\z', $val);
    }

    public function isValidNo_mondai($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidTm_cyokka($val)
    {
        return Validate::isFloat($val, 0);
    }

    public function isValidDt_hassei($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_kaito($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_kakunin($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidNm_biko($val)
    {
        return Validate::isText($val);
    }

    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }
}
