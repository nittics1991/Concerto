<?php

/**
*   mail_inf
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MailInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "ins_date" => parent::STRING
        , "from_tanto" => parent::STRING
        , "to_tanto" => parent::STRING
        , "nm_title" => parent::STRING
        , "nm_comment" => parent::STRING
        , "no_cyu" => parent::STRING
        , "no_seq" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "kb_nendo" => parent::STRING
        , "cd_type" => parent::STRING
        , "from_adr" => parent::STRING
        , "to_adr" => parent::STRING
        , "cc_adr" => parent::STRING
        , "no_page" => parent::INTEGER
        , "cd_sts" => parent::STRING
        , "fg_end" => parent::STRING
        , "fg_cancel" => parent::STRING
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    /**
    *   メールタイプ
    *
    *   @var array
    */
    private $cd_type_list = [
        '1' => 'ワークフロー',
        '2' => 'クレーム',
        '3' => '設備予約',
        '4' => '外注',
        '5' => 'VOC',
        '6' => '出荷申請',
        '7' => '外注購入事前通知',
        '8' => '購入払出申請',
        '9' => '購入払出準備済',
        '10' => '外注購入受入済',
        '11' => 'フォローメール',
        '12' => '見積台帳',
        '13' => '一般申請',
        '14' => '製番完成期限',
        '15' => 'WF懸案',
    ];

    /**
    *   複数メールアドレス文字列 配列変換
    *
    *   @param string $address メールアドレス
    *   @param string $delimiter 区切り文字
    *   @return array アドレス配列
    */
    public function parseAddress($address, $delimiter = ';')
    {
        $explode = explode($delimiter, $address);

        $result = [];
        foreach ((array)$explode as $adr) {
            if (mb_strlen(trim($adr)) > 0) {
                $result[] = $adr;
            }
        }
        return $result;
    }



    public function isValidIns_date($val)
    {
        return Validate::isTextDateTime($val);
    }

    public function isValidFrom_tanto($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidTo_tanto($val)
    {
        return Validate::isTanto($val);
    }

    //nm_title
    //nm_comment

    public function isValidNo_cyu($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isCyuban($val);
    }

    public function isValidNo_seq($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextInt($val, 0);
    }

    public function isValidCd_bumon($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isBumon($val);
    }

    public function isValidKb_Nendo($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isNendo($val);
    }

    public function isValidCd_type($val)
    {
        return Validate::isTextInt($val, 1);
    }

    public function isValidFrom_adr($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isEmailText($val);
    }

    public function isValidTo_adr($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isEmailText($val);
    }

    public function isValidCc_adr($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isEmailText($val);
    }

    public function isValidNo_page($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isInt($val, 0);
    }

    //cd_sts

    /**
    *   メールタイプ取得
    *
    *   @param string|null $id コード番号
    *   @return string|array|null
    */
    public function getCdType($id = null)
    {
        if (is_null($id)) {
            return $this->cd_type_list;
        }

        if (array_key_exists($id, $this->cd_type_list)) {
            return $this->cd_type_list[$id];
        }
        return null;
    }

    public function isValidFg_end($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidFg_cancel($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTextBool($val);
    }
}
