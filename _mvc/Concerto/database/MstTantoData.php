<?php

/**
*   mst_tanto
*
*   @version 240214
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\MstBumonData;
use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int>
*/
class MstTantoData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'cd_tanto' => parent::STRING,
        'disp_seq' => parent::STRING,
        'nm_tanto' => parent::STRING,
        'mail_add' => parent::STRING,
        'kengen_db' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'fg_mail' => parent::STRING,
        'ri_cyokka' => parent::INTEGER,
        'username' => parent::STRING,
        'cd_hash' => parent::STRING,
        'dt_hash' => parent::STRING,
        'dt_delete' => parent::STRING,
        'nm_group' => parent::STRING,
    ];

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidDisp_seq(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_tanto(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidMail_add(
        mixed $val
    ): bool {
        return Validate::isEmail($val);
    }

    public function isValidKengen_db(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidFg_mail(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidRi_cyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 100);
    }

    public function isValidUsername(
        mixed $val
    ): bool {
        return Validate::isUser($val);
    }

    public function isValidCd_hash(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_hash(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidDt_delete(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidNm_group(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
