<?php

/**
*   cyuban_inf
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\{
    CyunyuInfData,
    MitumoriInfData,
    MstBumonData,
    MstTantoData
};
use Concerto\standard\ModelData;
use Concerto\Validate;

class CyubanInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'kb_nendo' => parent::STRING
        , 'no_cyu' => parent::STRING
        , 'cd_bumon' => parent::STRING
        , 'dt_puriage' => parent::STRING
        , 'kb_ukeoi' => parent::STRING
        , 'kb_cyumon' => parent::STRING
        , 'nm_syohin' => parent::STRING
        , 'nm_setti' => parent::STRING
        , 'nm_user' => parent::STRING
        , 'dt_uriage' => parent::STRING
        , 'kb_keikaku' => parent::STRING
        , 'no_seq' => parent::INTEGER
        , 'dt_hatuban' => parent::STRING
        , 'nm_tanto' => parent::STRING
        , 'dt_hakkou' => parent::STRING
        , 'yn_sp' => parent::INTEGER
        , 'yn_net' => parent::INTEGER
        , 'cd_kisyu' => parent::STRING
        , 'kb_kubun' => parent::STRING
        , 'cd_tanto' => parent::STRING
        , 'no_mitumori' => parent::STRING
        , 'ri_mritu' => parent::INTEGER
        , 'nm_tanto_sien' => parent::STRING
        , 'dt_pnoki' => parent::STRING
        , 'no_user_cyumon' => parent::STRING
        , 'no_user_seizo' => parent::STRING
        , 'cd_sinki' => parent::STRING
        , 'cd_keijyou' => parent::STRING
        , 'cd_karikaku' => parent::STRING
        , 'nm_kaisyu_keitai' => parent::STRING
        , 'nm_kaisyu_kin' => parent::STRING
        , 'no_tegata' => parent::STRING
        , 'cd_simuketi' => parent::STRING
        , 'cd_syonin' => parent::STRING
        , 'dt_syonin' => parent::STRING
        , 'cd_tanto' => parent::STRING
    ];

    /**
    *   注文確度
    *
    *   @var array
    */
    private $kb_cyumon_list = ['受', 'Ａ', 'Ｂ', 'Ｃ', '仮'];

    /**
    */
    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidNo_cyu($val)
    {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A([A-Z,0-9]{7,8})\z', $val);
    }

    public function isValidCd_bumon($val)
    {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidDt_puriage($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidKb_ukeoi($val)
    {
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidKb_cyumon($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidNm_syohin($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_setti($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_user($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_uriage($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidKb_keikaku($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidDt_hatuban($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidNm_tanto($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_hakkou($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidyn_sp($val)
    {
        return Validate::isInt($val);
    }

    public function isValidyn_net($val)
    {
        return Validate::isInt($val);
    }

    public function isValidCd_kisyu($val)
    {
        return Validate::isAscii($val, 2, 2);
    }

    public function isValidKb_kubun($val)
    {
        return Validate::isAscii($val, 2, 2);
    }

    public function isValidCd_tanto($val)
    {
        return MstTantoData::validalidCd_tanto($val);
    }

    public function isValidNo_mitumori($val)
    {
        return MitumoriInfData::validalidNo_mitumori($val);
    }

    public function isValidRi_mritu($val)
    {
        return Validate::isInt($val);
    }

    public function isValidNm_tanto_sien($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_pnoki($val)
    {
        return Validate::isTextDateYYYYMMDD($val);
    }

    public function isValidNo_user_cyumon($val)
    {
        return Validate::isText($val);
    }

    public function isValidNo_user_seizo($val)
    {
        return Validate::isText($val);
    }

    public function isValidCd_sinki($val)
    {
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidCd_keijyou($val)
    {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidCd_karikaku($val)
    {
        return $val === '' ||
            Validate::isTextInt($val, 1, 2);
    }

    public function isValidNm_kaisyu_keitai($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_kaisyu_kin($val)
    {
        return Validate::isText($val);
    }

    public function isValidNo_tegata($val)
    {
        return Validate::isTextInt($val);
    }

    public function isValidCd_simuketi($val)
    {
        return Validate::isText($val);
    }

    public function isValidCd_syonin($val)
    {
        return Validate::isText($val);
    }

    public function isValidDt_syonin($val)
    {
        return Validate::isTextDateYYYYMMDD($val);
    }

    /**
    *   注文確度取得
    *
    *   @param string $id 注文確度
    *   @return string|array|null
    */
    public function getKbCyumon($id = null)
    {
        if (is_null($id)) {
            return $this->kb_cyumon_list;
        }

        if (array_key_exists($id, $this->kb_cyumon_list)) {
            return $this->kb_cyumon_list[$id];
        }
        return null;
    }
}
