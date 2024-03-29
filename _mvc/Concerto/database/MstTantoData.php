<?php

/**
*   mst_tanto
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\database\MstBumonData;
use Concerto\standard\ModelData;
use Concerto\Validate;

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
        'kengen' => parent::STRING,
        'kengen_db' => parent::STRING,
        'kengen_sm' => parent::STRING,
        'cd_bumon' => parent::STRING,
        'password' => parent::STRING,
        'fg_mail' => parent::STRING,
        'ri_cyokka' => parent::INTEGER,
        'username' => parent::STRING,
        'fg_cookie' => parent::STRING,
        'cd_hash' => parent::STRING,
        'dt_hash' => parent::STRING,
        'dt_delete' => parent::STRING,
        'kengen_login' => parent::STRING,
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

    public function isValidKengen(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidKengen_db(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }

    public function isValidKengen_sm(
        mixed $val
    ): bool {
        return Validate::isTextInt($val, 0, 3);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return MstBumonData::validCd_Bumon($val);
    }

    public function isValidPassword(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 5);
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

    public function isValidFg_cookie(
        mixed $val
    ): bool {
        return Validate::isText($val);
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

    public function isValidKengen_login(
        mixed $val
    ): bool {
        return Validate::isTextBool($val);
    }
}
