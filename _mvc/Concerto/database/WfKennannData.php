<?php

/**
*   wf_kennann
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
class WfKennannData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'no_seq' => parent::INTEGER,
        'cd_type' => parent::STRING,
        'nm_kennann' => parent::STRING,
        'dt_kigen' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'nm_taisaku' => parent::STRING,
        'dt_kakunin' => parent::STRING,
        'nm_kakunin' => parent::STRING,
        'fg_kennann' => parent::STRING,
        'cd_tanto_kanri' => parent::STRING,
        'nm_type' => parent::STRING,
    ];

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0);
    }

    public function isValidCd_type(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_kennann(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_kigen(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_tanto(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_taisaku(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidDt_kakunin(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextDate($val);
    }

    public function isValidNm_kakunin(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isText($val);
    }

    public function isValidFg_kennann(
        mixed $val
    ): bool {
        if (is_null($val) || $val === '') {
            return true;
        }
        return Validate::isTextBool($val);
    }

    public function isValidCd_tanto_kanri(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_type(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
