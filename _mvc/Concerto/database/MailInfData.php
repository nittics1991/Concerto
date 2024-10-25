<?php

/**
*   mail_inf
*
*   @version 240219
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MailInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'ins_date' => parent::STRING,
        'from_tanto' => parent::STRING,
        'to_tanto' => parent::STRING,
        'nm_title' => parent::STRING,
        'nm_comment' => parent::STRING,
        'no_cyu' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'cd_type' => parent::STRING,
        'from_adr' => parent::STRING,
        'to_adr' => parent::STRING,
        'cc_adr' => parent::STRING,
        'no_page' => parent::INTEGER,
        'cd_sts' => parent::STRING,
        'fg_end' => parent::STRING,
        'fg_cancel' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    private array $cd_type_list = [
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
        '16' => '発番新規',
        '17' => '発番承認',
        '18' => '製番完成未処理',
        '19' => '勤務登録',
        '20' => 'パスワードレスキュー',
        '21' => '外注発注通知',
    ];

    /**
    *   複数メールアドレス文字列 配列変換
    *
    *   @param string $address メールアドレス
    *   @param string $delimiter 区切り文字
    *   @return string[] アドレス配列
    */
    public function parseAddress(
        string $address,
        string $delimiter = ';'
    ): array {
        if (mb_strlen($delimiter) < 1) {
            throw new InvalidArgumentException(
                "must be delimiter length > 0",
            );
        }

        /** @var non-empty-string $delimiter */
        $explode = explode($delimiter, $address);

        $result = [];
        foreach ((array)$explode as $adr) {
            if (mb_strlen(trim($adr)) > 0) {
                $result[] = $adr;
            }
        }
        return $result;
    }



    public function isValidIns_date(
        mixed $val
    ): bool {
        return Validate::isTextDateTime($val);
    }

    public function isValidFrom_tanto(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidTo_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    //nm_title
    //nm_comment

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isCyuban($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isBumon($val);
    }

    public function isValidCd_type(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 1);
    }

    public function isValidFrom_adr(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isEmailText($val);
    }

    public function isValidTo_adr(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isEmailText($val);
    }

    public function isValidCc_adr(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isEmailText($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
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
    *   @return null|string|string[]
    */
    public function getCdType(
        ?string $id = null
    ): null|string|array {
        if (is_null($id)) {
            return $this->cd_type_list;
        }

        if (array_key_exists($id, $this->cd_type_list)) {
            return $this->cd_type_list[$id];
        }
        return null;
    }

    public function isValidFg_end(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidFg_cancel(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }
}
