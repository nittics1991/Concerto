<?php

/**
*   mst_skill_bumon
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
class MstSkillBumonData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_bumon' => parent::STRING,
        'nm_bumon' => parent::STRING,
        'cd_status' => parent::STRING,
        'disp_seq' => parent::STRING,
    ];

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val) &&
            mb_ereg_match('\AX\d{4}\z', strval($val));
    }

    public function isValidNm_bumon(
        mixed $val
    ): bool {
        return Validate::isTextEscape(
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
            mb_ereg_match('\A[\x20-\x7eぁ-ん]+\z', $val);
    }
}
