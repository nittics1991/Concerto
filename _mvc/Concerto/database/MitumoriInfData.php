<?php

/**
*   mitumori_inf
*
*   @version 230509
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MitumoriInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'ins_date' => parent::STRING,
        'no_mitumori' => parent::STRING,
        'nm_syohin' => parent::STRING,
        'nm_setti' => parent::STRING,
        'nm_user' => parent::STRING,
        'kb_nendo' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_tanto_sub' => parent::STRING,
        'dt_phatuban' => parent::STRING,
        'dt_puriage' => parent::STRING,
        'dt_pgentyo' => parent::STRING,
        'cd_mitumori_type' => parent::STRING,
        'cd_kessai_type' => parent::STRING,
        'no_cyu_t' => parent::STRING,
        'no_kanren' => parent::STRING,
        'nm_eigyo' => parent::STRING,
        'nm_gijyutu' => parent::STRING,
        'kb_mukou' => parent::STRING,
        'kb_cyumon' => parent::STRING,
        'nm_biko' => parent::STRING,
        'cd_bunya' => parent::INTEGER,
        'cd_bunrui' => parent::INTEGER,
        'yn_sp' => parent::INTEGER,
        'yn_tov' => parent::INTEGER,
        'yn_soneki' => parent::INTEGER,
        'tm_cyokka_hw' => parent::INTEGER,
        'tm_cyokka_sw' => parent::INTEGER,
        'tm_gentyo' => parent::INTEGER,
        'cd_kisyu' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    protected array $rank = [
        '0' => '仮',
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'J',
        '6' => '失',
    ];

    /**
    *   @var string[]
    */
    protected array $mitumoriType = [
        '1' => '概算',
        '2' => '正式',
        '3' => 'その他',
    ];

    /**
    *   @var string[]
    */
    protected array $kessaiType = [
        '1' => '見積決裁',
        '2' => '受注決裁',
    ];

    /**
    *   ランク
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getRank(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->rank;
        }
        if (array_key_exists($key, $this->rank)) {
            return $this->rank[$key];
        }
        return null;
    }

    /**
    *   見積決裁タイプ
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getMitumoriType(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->mitumoriType;
        }
        if (array_key_exists($key, $this->mitumoriType)) {
            return $this->mitumoriType[$key];
        }
        return null;
    }

    /**
    *   受注決裁タイプ
    *
    *   @param ?string $key
    *   @return null|string|string[]
    */
    public function getKessaiType(
        ?string $key = null
    ): null|string|array {
        if (is_null($key)) {
            return $this->kessaiType;
        }
        if (array_key_exists($key, $this->kessaiType)) {
            return $this->kessaiType[$key];
        }
        return null;
    }

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNo_mitumori(
        mixed $val
    ): bool {
        return Validate::isMitumoriNo($val);
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

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidCd_tanto_sub(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidDt_phatuban(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_puriage(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_pgentyo(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_mitumori_type(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 3);
    }

    public function isValidCd_kessai_type(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 1);
    }

    public function isValidNo_cyu_t(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNo_kanren(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_eigyo(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_gijyutu(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidKb_mukou(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidKb_cyumon(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 1, 6);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidCd_bunya(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_bunrui(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_sp(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidYn_tov(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidYn_soneki(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidTm_cyokka_hw(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidTm_cyokka_sw(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidTm_gentyo(
        mixed $val
    ): bool {
        return Validate::isInt($val);
    }

    public function isValidCd_kisyu(
        mixed $val
    ): bool {
        return Validate::isText($val, 2, 2);
    }
}
