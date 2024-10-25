<?php

/**
*   koban_inf
*
*   @version 230509
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\{
    CyubanInfData,
    MstBumonData
};
use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int|float>
*/
class KobanInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'kb_nendo' => parent::STRING,
        'no_cyu' => parent::STRING,
        'no_ko' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'dt_pkansei_m' => parent::STRING,
        'kb_cyumon' => parent::STRING,
        'nm_syohin' => parent::STRING,
        'yn_tov' => parent::INTEGER,
        'tm_pcyokka' => parent::DOUBLE,
        'yn_pcyokka' => parent::INTEGER,
        'yn_pcyokuzai' => parent::INTEGER,
        'yn_petc' => parent::INTEGER,
        'tm_ycyokka' => parent::DOUBLE,
        'yn_ycyokka' => parent::INTEGER,
        'yn_ycyokuzai' => parent::INTEGER,
        'yn_yetc' => parent::INTEGER,
        'tm_rcyokka' => parent::DOUBLE,
        'yn_rcyokka' => parent::INTEGER,
        'yn_rcyokuzai' => parent::INTEGER,
        'yn_retc' => parent::INTEGER,
        'dt_kansei' => parent::STRING,
        'dt_pkansei' => parent::STRING,
        'kb_keikaku' => parent::STRING,
        'yn_pcyunyu' => parent::INTEGER,
        'yn_ycyunyu' => parent::INTEGER,
        'yn_rcyunyu' => parent::INTEGER,
        'yn_psoneki' => parent::INTEGER,
        'yn_ysoneki' => parent::INTEGER,
        'yn_rsoneki' => parent::INTEGER,
        'dt_pnoki' => parent::STRING,
    ];

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return CyubanInfData::validNo_cyu($val);
    }

    public function isValidNo_ko(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A([A-Z,0-9]{4,5})\z', $val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidDt_pkansei_m(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
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

    public function isValidYn_tov(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidtm_pcyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val);
    }

    public function isValidyn_pcyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_pcyokuzai(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_petc(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidtm_ycyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val);
    }

    public function isValidyn_ycyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_ycyokuzai(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_yetc(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidtm_rcyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val);
    }

    public function isValidyn_rcyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_rcyokuzai(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidyn_retc(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidDt_kansei(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_pkansei(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidKb_keikaku(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    //yn_pcyunyu
    //yn_ycyunyu
    //yn_rcyunyu
    //yn_psoneki
    //yn_ysoneki
    //yn_rsoneki

    public function isValidDt_pnoki(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }
}
