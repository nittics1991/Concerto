<?php

/**
*   hicyokka_inf
*
*   @version 231102
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\{
    MstBumonData,
    MstTantoData
};
use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int|float>
*/
class HicyokkaInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
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
    *   @var string[]
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
    *   @return string[]
    */
    public function fixedBunruiList(): array
    {
        return $this->fixedl_bunrui_list;
    }

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return MstTantoData::validCd_tanto($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNm_hicyokka(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_bunrui(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return MstTantoData::validCd_tanto($val);
    }

    public function isValidDt_cyunyu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidTm_cyunyu(
        mixed $val
    ): bool {
        return Validate::isFloat($val);
    }

    public function isValidDt_yyyymm(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return MstBumonData::validCd_bumon($val);
    }

    public function isValidNm_iraimoto(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_link(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100) &&
            !Validate::hasTextHankaku($val);
    }
}
