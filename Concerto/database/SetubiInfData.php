<?php

/**
*   setubi_inf
*
*   @version 200605
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
        , 'kb_setubi' => parent::STRING
        , 'nm_setubi' => parent::STRING
        , 'nm_maker' => parent::STRING
        , 'no_model' => parent::STRING
        , 'no_seizo' => parent::STRING
        , 'ho_basyo' => parent::STRING
        , 'kb_group' => parent::STRING
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
    private $kb_setubi_list = [
        '00' => 'デバッグ室・会議スペース',
        '04' => 'パソコン機器',
        '05' => 'ソフトウェア',
        '06' => 'ＰＬＣ',
        '08' => '試験設備',
        '09' => 'その他',
    ];

    public function isValidCd_setubi($val)
    {
        return mb_ereg_match('\A[0-9]{4}\z', $val);
    }

    public function isValidKb_setubi($val)
    {
        return mb_ereg_match('\A[0-9]{2}\z', $val);
    }

    public function isValidNm_setubi($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }

    public function isValidNm_maker($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }

    public function isValidNo_model($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }

    public function isValidNo_seizo($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }

    public function isValidHo_basyo($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }

    public function isValidKb_group($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidNm_biko($val)
    {
        return Validate::isTextEscape($val, null, null, null, '')
            && !Validate::hasTextHankaku($val);
    }

    public function isValidDt_message($val)
    {
        return $val == '' ||
            Validate::isTextDate($val);
    }

    public function isValidNm_message($val)
    {
        return Validate::isTextEscape($val)
            && !Validate::hasTextHankaku($val);
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
            return $this->kb_setubi_list;
        }

        if (mb_strlen($id) == 1) {
            $id = sprintf("%02d", $id);
        }

        if (array_key_exists($id, $this->kb_setubi_list)) {
            return $this->kb_setubi_list[$id];
        }
        return null;
    }
}
