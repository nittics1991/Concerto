<?php

/**
*   mondaiten_inf
*
*   @version 221226
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int|float>
*/
class MondaitenInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'no_seq' => parent::INTEGER,
        'cd_hassei' => parent::STRING,
        'cd_bunrui' => parent::STRING,
        'no_mondai' => parent::INTEGER,
        'tm_cyokka' => parent::FLOAT,
        'dt_hassei' => parent::STRING,
        'dt_kaito' => parent::STRING,
        'dt_kakunin' => parent::STRING,
        'nm_biko' => parent::STRING,
        'up_date' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    private array $cd_hassei_list = [
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
    *   @param ?string $id
    *   @return null|string|string[]
    */
    public function getHasseiName(
        ?string $id = null
    ): null|string|array {
        if (is_null($id)) {
            return $this->cd_hassei_list;
        }

        if (array_key_exists($id, $this->cd_hassei_list)) {
            return $this->cd_hassei_list[$id];
        }
        return null;
    }


    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_hassei(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 5);
    }

    public function isValidCd_bunrui(
        mixed $val
    ): bool {
        return Validate::isText($val, 2, 2) &&
            mb_ereg_match('\A[0-9A-Z]{2}\z', strval($val));
    }

    public function isValidNo_mondai(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidTm_cyokka(
        mixed $val
    ): bool {
        return Validate::isFloat($val, 0);
    }

    public function isValidDt_hassei(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_kaito(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_kakunin(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }
}
