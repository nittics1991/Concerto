<?php

/**
*   hicyokka_inf
*
*   @version 220308
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\{
    MstBumonData,
    MstTantoData
};
use Concerto\standard\ModelData;
use Concerto\Validate;

class HicyokkaInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'nm_hicyokka' => parent::STRING,
        'nm_bunrui' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'dt_cyunyu' => parent::STRING,
        'tm_cyunyu' => parent::DOUBLE,
        'dt_yyyymm' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'nm_iraimoto' => parent::STRING,
        'nm_link' => parent::STRING,
        'nm_biko' => parent::STRING,
    ];

    /**
    *   分類リスト初期値
    *
    *   @var array
    */
    protected array $fixedl_bunrui_list = [
        '不具合',
        '受注活動',
        'スタッフ業務',
        '技術活動',
        '教育',
        '管理業務',
        'その他',
        ];

    /**
    *   分類固定リスト
    *
    *   @return array
    */
    public function fixedBunruiList(): array
    {
        return $this->fixedl_bunrui_list;
    }

    public function isValidUp_date($val)
    {
        return Validate::isTextDateTime($val);
    }

    public function isValidEditor($val)
    {
        return MstTantoData::validCd_tanto($val);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0, 30000);
    }

    public function isValidNm_hicyokka($val)
    {
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_bunrui($val)
    {
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidCd_tanto($val)
    {
        return MstTantoData::validCd_tanto($val);
    }

    public function isValidDt_cyunyu($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidTm_cyunyu($val)
    {
        return Validate::isFloat($val);
    }

    public function isValidDt_yyyymm($val)
    {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_bumon($val)
    {
        return MstBumonData::validCd_bumon($val);
    }

    public function isValidNm_iraimoto($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_link($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_biko($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }
}
