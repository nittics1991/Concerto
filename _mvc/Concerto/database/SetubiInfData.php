<?php

/**
*   setubi_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string>
*/
class SetubiInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_setubi' => parent::STRING,
        'cd_group' => parent::STRING,
        'nm_setubi' => parent::STRING,
        'nm_bunrui' => parent::STRING,
        'no_model' => parent::STRING,
        'no_kanri' => parent::STRING,
        'nm_hokanbasyo' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'nm_biko' => parent::STRING,
        'dt_message' => parent::STRING,
        'nm_message' => parent::STRING,
    ];

    /**
    *   @var string[]
    */
    private array $cd_group_list = [
        '00' => 'デバッグ室・会議スペース',
        '01' => 'パソコン機器',
        '02' => 'ソフトウェア',
        '03' => 'ＰＬＣ',
        '04' => '試験設備',
        '09' => 'その他',
    ];

    public function isValidCd_setubi(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match('\A[0-9]{4}\z', $val);
    }

    public function isValidCd_group(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match('\A[0-9]{2}\z', $val);
    }

    public function isValidNm_setubi(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_bunrui(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNo_model(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNo_kanri(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidNm_hokanbasyo(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
            $val,
            null,
            null,
            null,
            ''
        ) &&
            !Validate::hasTextHankaku($val);
    }

    public function isValidDt_message(
        mixed $val
    ): bool {
        return $val === '' ||
            Validate::isTextDate($val);
    }

    public function isValidNm_message(
        mixed $val
    ): bool {
        return Validate::isTextEscape($val) &&
            !Validate::hasTextHankaku($val);
    }

    /**
    *   グループ取得
    *
    *   @param null|string|int $id
    *   @return null|string|string[]
    */
    public function getGroup(
        null|string|int $id = null
    ): null|string|array {
        if (is_null($id)) {
            return $this->cd_group_list;
        }

        if (is_int($id)) {
            $id = strval($id);
        }

        if (mb_strlen($id) === 1) {
            $id = sprintf("%02d", $id);
        }

        if (array_key_exists($id, $this->cd_group_list)) {
            return $this->cd_group_list[$id];
        }
        return null;
    }
}
