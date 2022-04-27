<?php

/**
*   mail_cc_inf
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MailCcInfData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        "cd_type" => parent::STRING
        , "no_seq" => parent::INTEGER
        , "cd_tanto" => parent::STRING
        , "cd_system" => parent::STRING
    ];

    /**
    *   タイプ
    *
    *   @var array
    */
    protected $mail_cc_type = [
        '1' => 'WF承認',
        '2' => 'クレーム承認',
        '3' => '設備登録フォロー',
        '4' => '外注検収期限',
        // '5' => 'VOC通知',
        '6' => '出荷承認事前通知',
        // '7' => 'WF手配通知',
        '8' => '購入払出申請通知',
        '9' => '購入払出準備済通知',
        '10' => '購入品納品通知',
        // '11' => '見積更新フォロー',
        '12' => '製番完成期限',
        '13' => 'WF懸案',
    ];

    /**
    *   Column Alias
    *
    *   @var array
    */
    protected static $alias = [];

    public function isValidCd_type($val)
    {
        return Validate::isText($val);
    }

    public function isValidNo_seq($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_tanto($val)
    {
        if (is_null($val) || ($val == '')) {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_system($val)
    {
        return Validate::isCdSystem($val);
    }

    /**
    *   タイプ名取得
    *
    *   @param string|null $cd_type
    *   @return string|array
    */
    public function getMailCcType($cd_type = null)
    {
        if (!isset($cd_type)) {
            return $this->mail_cc_type;
        }

        return (isset($this->mail_cc_type[$cd_type])) ?
            $this->mail_cc_type[$cd_type] : '';
    }
}
