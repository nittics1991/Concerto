<?php

/**
*   mst_skill_bumon
*
*   @version 220203
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class MstSkillBumonData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'cd_bumon' => parent::STRING,
        'nm_bumon' => parent::STRING,
        'cd_status' => parent::STRING,
        'disp_seq' => parent::STRING,
    ];

    public function isValidCd_bumon($val)
    {
        return Validate::isBumon($val) &&
            mb_ereg_match('\AX\d{4}\z', $val);
    }

    public function isValidNm_bumon($val)
    {
        return Validate::isTextEscape($val, 0, null, null, '\r\n\t　') &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidCd_status($val)
    {
        if ($val == '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidDisp_seq($val)
    {
        return is_string($val) &&
            mb_check_encoding((string)$val) &&
            mb_ereg_match('\A[\x20-\x7eぁ-ん]+\z', $val);
    }
}
