<?php

/**
*   haraidasi_inf
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class HaraidasiInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
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
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    /**
    *   ステータスタイプ
    *
    *   @var array
    */
    private $cd_sts_list = [
        0 => '',
        1 => '申請中',
        2 => '払出準備済',
        3 => '受取済',
    ];

    /**
    *   ステータスタイプ取得
    *
    *   @param string|null $id ステータス番号
    *   @return string|array|null
    */
    public function getStatusName($id = null)
    {
        if (is_null($id)) {
            return $this->cd_sts_list;
        }

        if (array_key_exists($id, $this->cd_sts_list)) {
            return $this->cd_sts_list[$id];
        }
        return null;
    }


    public function isValidUp_date($val)
    {
        return Validate::isTextDate($val);
    }

    public function isValidEditor($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNo_cyumon($val)
    {
        return Validate::isCyumon($val);
    }

    public function isValidNo_bunno($val)
    {
        if (
            !is_string($val) ||
            !mb_check_encoding((string)$val) ||
            !mb_ereg_match('\A[0-9]{2}\z', $val)
        ) {
            return false;
        }
        return true;
    }

    public function isValidCd_sts($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0, 3);
    }

    public function isValidNo_adr($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_adr($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNo_tel($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_to($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidNm_biko($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidDt_sinsei($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextDateTime($val);
    }

    public function isValidDt_haraidasi($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextDateTime($val);
    }

    public function isValidDt_uketori($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextDateTime($val);
    }

    public function isValidCd_tanto($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_haraidasi($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_uketori($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTanto($val);
    }
}
