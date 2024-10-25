<?php

/**
*   haraidasi_inf
*
*   @version 221220
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class HaraidasiInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_cyumon' => parent::STRING,
        'no_bunno' => parent::STRING,
        'cd_sts' => parent::INTEGER,
        'no_adr' => parent::STRING,
        'nm_adr' => parent::STRING,
        'no_tel' => parent::STRING,
        'nm_to' => parent::STRING,
        'nm_biko' => parent::STRING,
        'dt_sinsei' => parent::STRING,
        'dt_haraidasi' => parent::STRING,
        'dt_uketori' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_haraidasi' => parent::STRING,
        'cd_uketori' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    private array $cd_sts_list = [
        0 => '',
        1 => '申請中',
        2 => '払出準備済',
        3 => '受取済',
    ];

    /**
    *   ステータスタイプ取得
    *
    *   @param ?int $id
    *   @return null|string|string[]
    */
    public function getStatusName(
        ?int $id = null
    ): null|string|array {
        if (is_null($id)) {
            return $this->cd_sts_list;
        }

        if (array_key_exists($id, $this->cd_sts_list)) {
            return $this->cd_sts_list[$id];
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

    public function isValidNo_cyumon(
        mixed $val
    ): bool {
        return Validate::isCyumon($val);
    }

    public function isValidNo_bunno(
        mixed $val
    ): bool {
        if (
            !is_string($val) ||
            !mb_check_encoding((string)$val) ||
            !mb_ereg_match('\A[0-9]{2}\z', $val)
        ) {
            return false;
        }
        return true;
    }

    public function isValidCd_sts(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0, 3);
    }

    public function isValidNo_adr(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_adr(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNo_tel(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_to(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidDt_sinsei(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDateTime($val);
    }

    public function isValidDt_haraidasi(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDateTime($val);
    }

    public function isValidDt_uketori(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_haraidasi(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_uketori(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }
}
