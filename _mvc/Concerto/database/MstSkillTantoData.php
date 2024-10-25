<?php

/**
*   MstSkillTantoData
*
*   @version 221226
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string>
*/
class MstSkillTantoData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_tanto' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'cd_status' => parent::STRING,
        'disp_seq' => parent::STRING,
        'cd_bumon' => parent::STRING,
    ];

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val) &&
            mb_ereg_match('\80\d{3}\z', strval($val));
    }

    public function isValidNm_tanto(
        mixed $val
    ): bool {
        return
            Validate::isTextEscape(
                $val,
                0,
                null,
                null,
                '\r\n\t　'
            ) &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidCd_status(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidDisp_seq(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A[\x20-\x7eぁ-ん]+\z', strval($val));
    }

    public function isValidCd_bumn(
        mixed $val
    ): bool {
        return Validate::isBumon($val) &&
            mb_ereg_match('\AXd{4}\z', strval($val));
    }
}
