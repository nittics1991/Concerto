<?php

/**
*   setubi_inf
*
*   @version 210805
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class SetubiInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_setubi' => parent::STRING
        , 'cd_group' => parent::STRING
        , 'nm_setubi' => parent::STRING
        , 'nm_bunrui' => parent::STRING
        , 'no_model' => parent::STRING
        , 'no_kanri' => parent::STRING
        , 'nm_hokanbasyo' => parent::STRING
        , 'cd_bumon' => parent::STRING
        , 'cd_tanto' => parent::STRING
        , 'nm_biko' => parent::STRING
        , 'dt_message' => parent::STRING
        , 'nm_message' => parent::STRING
    ];

    /**
    *   グループ
    *
    *   @var array
    */
    private $cd_group_list = [
        '00' => 'デバッグ室・会議スペース',
        '01' => 'パソコン機器',
        '02' => 'ソフトウェア',
        '03' => 'ＰＬＣ',
        '04' => '試験設備',
        '09' => 'その他',
    ];

    public function isValidCd_setubi($val)
    {
        return mb_ereg_match('\A[0-9]{4}\z', $val);
    }

    public function isValidCd_group($val)
    {
        return mb_ereg_match('\A[0-9]{2}\z', $val);
    }

    public function isValidNm_setubi($val)
    {
        return Validate::isTextEscape($val, null, null, null, '') &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_bunrui($val)
    {
        return Validate::isTextEscape($val, null, null, null, '') &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNo_model($val)
    {
        return Validate::isTextEscape($val, null, null, null, '') &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNo_kanri($val)
    {
        return Validate::isTextEscape($val, null, null, null, '') &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_hokanbasyo($val)
    {
        return Validate::isTextEscape($val, null, null, null, '') &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNm_biko($val)
    {
        return Validate::isTextEscape($val, null, null, null, '') &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidDt_message($val)
    {
        return $val == '' ||
            Validate::isTextDate($val);
    }

    public function isValidNm_message($val)
    {
        return Validate::isTextEscape($val) &&
            !Validate::hasTextHankaku($val);
    }

    /**
    *   グループ取得
    *
    *   @param string|int|null $id
    *   @return string|array|null
    */
    public function getGroup($id = null)
    {
        if (is_null($id)) {
            return $this->cd_group_list;
        }

        if (mb_strlen($id) == 1) {
            $id = sprintf("%02d", $id);
        }

        if (array_key_exists($id, $this->cd_group_list)) {
            return $this->cd_group_list[$id];
        }
        return null;
    }
}
