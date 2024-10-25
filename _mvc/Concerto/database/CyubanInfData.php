<?php

/**
*   cyuban_inf
*
*   @version 230509
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

/**
*   @extends ModelData<string|int>
*/
class CyubanInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'kb_nendo' => parent::STRING,
        'no_cyu' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'dt_puriage' => parent::STRING,
        'kb_ukeoi' => parent::STRING,
        'kb_cyumon' => parent::STRING,
        'nm_syohin' => parent::STRING,
        'nm_setti' => parent::STRING,
        'nm_user' => parent::STRING,
        'dt_uriage' => parent::STRING,
        'kb_keikaku' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'dt_hatuban' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'dt_hakkou' => parent::STRING,
        'yn_sp' => parent::INTEGER,
        'yn_net' => parent::INTEGER,
        'cd_kisyu' => parent::STRING,
        'kb_kubun' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'no_mitumori' => parent::STRING,
        'ri_mritu' => parent::INTEGER,
        'nm_tanto_sien' => parent::STRING,
        'dt_pnoki' => parent::STRING,
        'no_user_cyumon' => parent::STRING,
        'no_user_seizo' => parent::STRING,
        'cd_sinki' => parent::STRING,
        'cd_keijyou' => parent::STRING,
        'cd_karikaku' => parent::STRING,
        'nm_kaisyu_keitai' => parent::STRING,
        'nm_kaisyu_kin' => parent::STRING,
        'no_tegata' => parent::STRING,
        'cd_simuketi' => parent::STRING,
        'cd_syonin' => parent::STRING,
        'dt_syonin' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    private array $kb_cyumon_list = [
        '受', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', '仮'
    ];

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A([A-Z,0-9]{7,8})\z', $val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidDt_puriage(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidKb_ukeoi(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidKb_cyumon(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 5);
    }

    public function isValidNm_syohin(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_setti(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_user(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_uriage(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidKb_keikaku(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidDt_hatuban(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_tanto(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_hakkou(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidyn_sp(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_net(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidCd_kisyu(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 2, 2);
    }

    public function isValidKb_kubun(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 2, 2);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return MstTantoData::validalidCd_tanto($val);
    }

    public function isValidNo_mitumori(
        mixed $val
    ): bool {
        return MitumoriInfData::validalidNo_mitumori($val);
    }

    public function isValidRi_mritu(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidNm_tanto_sien(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_pnoki(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMMDD($val);
    }

    public function isValidNo_user_cyumon(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNo_user_seizo(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidCd_sinki(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 2);
    }

    public function isValidCd_keijyou(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 4);
    }

    public function isValidCd_karikaku(
        mixed $val
    ): bool {
        return $val === '' ||
            Validate::isTextInt($val, 1, 2);
    }

    public function isValidNm_kaisyu_keitai(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_kaisyu_kin(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNo_tegata(
        mixed $val
    ): bool {
        return Validate::isTextInt($val);
    }

    public function isValidCd_simuketi(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidCd_syonin(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_syonin(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMMDD($val);
    }

    /**
    *   注文確度取得
    *
    *   @param ?string $id 注文確度
    *   @return null|string|string[]
    */
    public function getKbCyumon(
        ?string $id = null
    ): null|string|array {
        if (is_null($id)) {
            return $this->kb_cyumon_list;
        }

        if (array_key_exists($id, $this->kb_cyumon_list)) {
            return $this->kb_cyumon_list[$id];
        }
        return null;
    }
}
