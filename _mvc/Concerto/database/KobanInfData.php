<?php

/**
*   koban_inf
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\CyubanInfData;
use Concerto\database\MstBumonData;
use Concerto\standard\ModelData;
use Concerto\Validate;

class KobanInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "kb_nendo" => parent::STRING
        , "no_cyu" => parent::STRING
        , "no_ko" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "dt_pkansei_m" => parent::STRING
        , "kb_cyumon" => parent::STRING
        , "nm_syohin" => parent::STRING
        , "yn_tov" => parent::INTEGER
        , "tm_pcyokka" => parent::DOUBLE
        , "yn_pcyokka" => parent::INTEGER
        , "yn_pcyokuzai" => parent::INTEGER
        , "yn_petc" => parent::INTEGER
        , "tm_ycyokka" => parent::DOUBLE
        , "yn_ycyokka" => parent::INTEGER
        , "yn_ycyokuzai" => parent::INTEGER
        , "yn_yetc" => parent::INTEGER
        , "tm_rcyokka" => parent::DOUBLE
        , "yn_rcyokka" => parent::INTEGER
        , "yn_rcyokuzai" => parent::INTEGER
        , "yn_retc" => parent::INTEGER
        , "dt_kansei" => parent::STRING
        , "dt_pkansei" => parent::STRING
        , "kb_keikaku" => parent::STRING
        , "yn_pcyunyu" => parent::INTEGER
        , "yn_ycyunyu" => parent::INTEGER
        , "yn_rcyunyu" => parent::INTEGER
        , "yn_psoneki" => parent::INTEGER
        , "yn_ysoneki" => parent::INTEGER
        , "yn_rsoneki" => parent::INTEGER
        , "dt_pnoki" => parent::STRING
    ];

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
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A([A-Z,0-9]{4,5})\z', $val);
    }

    public function isValidCd_bumon($val)
    {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidDt_pkansei_m($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidKb_cyumon($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidNm_syohin($val)
    {
        return Validate::isText($val);
    }

    public function isValidYn_tov($val)
    {
        return Validate::isInt($val);
    }

    public function isValidtm_pcyokka($val)
    {
        return Validate::isDouble($val);
    }

    public function isValidyn_pcyokka($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_pcyokuzai($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_petc($val)
    {
        return Validate::isInt($val);
    }

    public function isValidtm_ycyokka($val)
    {
        return Validate::isDouble($val);
    }

    public function isValidyn_ycyokka($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_ycyokuzai($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_yetc($val)
    {
        return Validate::isInt($val);
    }

    public function isValidtm_rcyokka($val)
    {
        return Validate::isDouble($val);
    }

    public function isValidyn_rcyokka($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_rcyokuzai($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_retc($val)
    {
        return Validate::isInt($val);
    }

    public function isValidDt_kansei($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidDt_pkansei($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidKb_keikaku($val)
    {
        return Validate::isTextBool($val);
    }

    //yn_pcyunyu
    //yn_ycyunyu
    //yn_rcyunyu
    //yn_psoneki
    //yn_ysoneki
    //yn_rsoneki

    public function isValidDt_pnoki($val)
    {
        return Validate::isTextDate($val);
    }
}
