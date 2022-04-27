<?php

/**
*   cyunyu_inf
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\{
    CyubanInfData,
    KobanInfData,
    MstBumonData,
    MstTantoData
};
use Concerto\standard\ModelData;
use Concerto\Validate;

class CyunyuInfData extends ModelData
{
    /**
    *   cd_tanto
    *
    *   @var string
    */
    public const CD_TANTO_UNDEFINED = 'XXXXXXXX';

    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "kb_nendo" => parent::STRING
        , "no_cyu" => parent::STRING
        , "no_ko" => parent::STRING
        , "dt_kanjyo" => parent::STRING
        , "cd_genka_yoso" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "dt_cyunyu" => parent::STRING
        , "tm_cyokka" => parent::DOUBLE
        , "yn_cyokka" => parent::INTEGER
        , "yn_cyokuzai" => parent::INTEGER
        , "yn_etc" => parent::INTEGER
        , "nm_tanto" => parent::STRING
        , "nm_syohin" => parent::STRING
        , "kb_cyunyu" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "no_cyumon" => parent::STRING
        , "no_seq" => parent::INTEGER
        , "up_date" => parent::STRING
        , "cd_rev" => parent::STRING
        , "no_tehai" => parent::STRING
        , "nm_tehai" => parent::STRING
        , "cd_furikae" => parent::STRING
        , "no_cyu_furikae" => parent::STRING
        , "no_ko_furikae" => parent::STRING
    ];

    /**
    *   原価要素コード
    *
    *   @var array
    */
    private $cd_genka_yoso_list = ['A' => '直材費', 'C1' => '直課費', 'C' => '経費'];

    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidNo_cyu($val)
    {
        return CyubanInfData::validNo_cyu($val);
    }

    public function isValidNo_ko($val)
    {
        return KobanInfData::validNo_ko($val);
    }

    public function isValidDt_kanjyo($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_genka_yoso($val)
    {
        return Validate::isGenkaYoso($val);
    }

    public function isValidCd_tanto($val)
    {
        if ($val == static::CD_TANTO_UNDEFINED) {
            return true;
        }
        return MstTantoData::validCd_tanto($val);
    }

    public function isValidDt_cyunyu($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidTm_cyokka($val)
    {
        return Validate::isDouble($val);
    }

    public function isValidYn_cyokka($val)
    {
        return Validate::isInt($val);
    }

    public function isValidYn_cyokuzai($val)
    {
        return Validate::isInt($val);
    }

    public function isValidYn_etc($val)
    {
        return Validate::isInt($val);
    }

    public function isValidNm_tanto($val)
    {
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_syohin($val)
    {
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidKb_cyunyu($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidCd_bumon($val)
    {
        return MstBumonData::validCd_bumon($val);
    }

    public function isValidNo_cyumon($val)
    {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match(
                '\A(K|G|J)[A-Z,0-9]{3}[0-9]{5}((\-)([0-9]{2}))*\z',
                $val
            );
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidUp_date($val)
    {
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_rev($val)
    {
        return Validate::isAscii($val);
    }

    public function isValidNo_tehai($val)
    {
        return Validate::isAscii($val);
    }

    public function isValidNm_tehai($val)
    {
        return Validate::isTextEscape($val, null, null, null, '\r\n\t');
    }

    public function isValidCd_furikae($val)
    {
        return Validate::isText($val);
    }

    public function isValidNo_cyu_furikae($val)
    {
        return CyubanInfData::validNo_cyu($val);
    }

    public function isValidNo_ko_furikae($val)
    {
        return KobanInfData::validNo_ko($val);
    }

    /**
    *   原価要素コード取得
    *
    *   @param string $id 注文確度
    *   @return string|array|null
    */
    public function getCdGenkaYoso($id = null)
    {
        if (is_null($id)) {
            return $this->cd_genka_yoso_list;
        }

        if (array_key_exists($id, $this->cd_genka_yoso_list)) {
            return $this->cd_genka_yoso_list[$id];
        }
        return null;
    }
}
