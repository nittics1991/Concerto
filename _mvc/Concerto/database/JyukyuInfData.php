<?php

/**
*   jyukyu_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class JyukyuInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'up_date' => parent::STRING,
        'editor' => parent::STRING,
        'no_jyukyu' => parent::STRING,
        'no_cyu' => parent::STRING,
        'nm_syohin' => parent::STRING,
        'nm_model' => parent::STRING,
        'dt_pjyukyu' => parent::STRING,
        'dt_rjyukyu' => parent::STRING,
        'dt_syukka' => parent::STRING,
        'cd_jyukyu_tanto' => parent::STRING,
        'nm_biko' => parent::STRING,
        'no_psuryo' => parent::INTEGER,
        'no_rsuryo' => parent::INTEGER,
    ];

    public function isValidUp_date(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNo_jyutyu(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match('\AJ[A-Z]{3}\d{5}\z', $val);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNm_syohin(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_model(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_pjyukyu(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_rjyukyu(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidDt_syukka(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidCd_ukeire_tanto(
        mixed $val
    ): bool {
        if ($val === '') {
            return true;
        }
        return Validate::isTanto($val);
    }

    public function isValidNm_biko(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNo_psuryo(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_rsuryo(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }
}
