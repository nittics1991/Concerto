<?php

/**
*   mitumori_inf
*
*   @version 210119
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MitumoriInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
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
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    /**
    *   ランク
    *
    *   @var array
    */
    protected $rank = [
        '0' => '仮',
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'J',
        '6' => '失',
    ];

    /**
    *   見積決裁タイプ
    *
    *   @var array
    */
    protected $mitumoriType = [
        '1' => '概算',
        '2' => '正式',
        '3' => 'その他',
    ];

    /**
    *   受注決裁タイプ
    *
    *   @var array
    */
    protected $kessaiType = [
        '1' => '見積決裁',
        '2' => '受注決裁',
    ];

    /**
    *   ランク
    *
    *   @param string|null $key
    *   @return string|array
    */
    public function getRank($key = null)
    {
        if (is_null($key)) {
            return $this->rank;
        }
        if (array_key_exists($key, $this->rank)) {
            return $this->rank[$key];
        }
        return;
    }

    /**
    *   見積決裁タイプ
    *
    *   @param string|null $key
    *   @return string|array
    */
    public function getMitumoriType($key = null)
    {
        if (is_null($key)) {
            return $this->mitumoriType;
        }
        if (array_key_exists($key, $this->mitumoriType)) {
            return $this->mitumoriType[$key];
        }
        return;
    }

    /**
    *   受注決裁タイプ
    *
    *   @param string|null $key
    *   @return string|array
    */
    public function getKessaiType($key = null)
    {
        if (is_null($key)) {
            return $this->kessaiType;
        }
        if (array_key_exists($key, $this->kessaiType)) {
            return $this->kessaiType[$key];
        }
        return;
    }

    /**
    *   Validate
    *
    */
    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidIns_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidNo_mitumori($val)
    {
        return Validate::isMitumoriNo($val);
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

    public function isValidKb_nendo($val)
    {
        return Validate::isNendo($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidCd_tanto_sub($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidDt_phatuban($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_puriage($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_pgentyo($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_mitumori_type($val)
    {
        return Validate::isTextInt($val, 0, 3);
    }

    public function isValidCd_kessai_type($val)
    {
        return Validate::isTextInt($val, 0, 1);
    }

    public function isValidNo_cyu_t($val)
    {
        return Validate::isText($val);
    }

    public function isValidNo_kanren($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_eigyo($val)
    {
        return Validate::isText($val);
    }

    public function isValidNm_gijyutu($val)
    {
        return Validate::isText($val);
    }

    public function isValidKb_mukou($val)
    {
        return Validate::isTextBool($val);
    }

    public function isValidKb_cyumon($val)
    {
        return Validate::isTextInt($val, 1, 6);
    }

    public function isValidKb_cyumon_zn($val)
    {
        return Validate::isTextInt($val, 1, 6);
    }

    public function isValidNm_biko($val)
    {
        return Validate::isText($val);
    }

    public function isValidCd_bunya($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_bunrui($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_sp($val)
    {
        return Validate::isInt($val);
    }

    public function isValidYn_tov($val)
    {
        return Validate::isInt($val);
    }

    public function isValidYn_soneki($val)
    {
        return Validate::isInt($val);
    }

    public function isValidTm_cyokka_hw($val)
    {
        return Validate::isInt($val);
    }

    public function isValidTm_cyokka_sw($val)
    {
        return Validate::isInt($val);
    }

    public function isValidTm_gentyo($val)
    {
        return Validate::isInt($val);
    }

    public function isValidCd_kisyu($val)
    {
        return Validate::isText($val, 2, 2);
    }
}
