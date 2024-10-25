<?php

/**
*   mail_cc_inf
*
*   @version 240425
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MailCcInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_type' => parent::STRING,
        'no_seq' => parent::INTEGER,
        'cd_tanto' => parent::STRING,
        'cd_system' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    protected array $mail_cc_type = [
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
        '14' => '発番新規',
        '15' => '発番承認',
        '16' => '製番完成未処理通知',
        '17' => '勤務登録',
        '18' => '調達品発注日通知',
    ];

    public function isValidCd_type(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidCd_system(
        mixed $val
    ): bool {
        return Validate::isCdSystem($val);
    }

    /**
    *   タイプ名取得
    *
    *   @param ?string $cd_type
    *   @return string|string[]
    */
    public function getMailCcType(
        ?string $cd_type = null
    ): string|array {
        if (!isset($cd_type)) {
            return $this->mail_cc_type;
        }

        return (isset($this->mail_cc_type[$cd_type])) ?
            $this->mail_cc_type[$cd_type] : '';
    }
}
