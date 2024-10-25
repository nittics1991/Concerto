<?php

/**
*   cyokka_mon_keikaku
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

/**
*   @extends ModelData<string|int|float>
*/
class CyokkaMonKeikakuData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        "kb_nendo" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "dt_yyyymm" => parent::STRING
        , "dt_kado" => parent::DOUBLE
        , "tm_zitudo" => parent::DOUBLE
        , "tm_teizikan" => parent::DOUBLE
        , "tm_zangyo" => parent::DOUBLE
        , "tm_cyokka" => parent::DOUBLE
        , "tm_zitudo_m" => parent::DOUBLE
        , "tm_teizikan_m" => parent::DOUBLE
        , "tm_zangyo_m" => parent::DOUBLE
        , "tm_cyokka_m" => parent::DOUBLE
        , "tm_hoyu_cyokka" => parent::INTEGER
        , "yn_yosan" => parent::INTEGER
        , "yn_soneki" => parent::INTEGER
    ];

    public function isValidKb_nendo(
        mixed $val
    ): bool {
        return Validate::isNendo($val);
    }

    public function isValidCd_bumon(
        mixed $val
    ): bool {
        return Validate::isBumon($val);
    }

    public function isValidDt_yyyymm(
        mixed $val
    ): bool {
        return Validate::isTextDateYYYYMM($val);
    }

    public function isValidDt_kado(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 31) ||
            Validate::isFloat($val, 0, 31);
    }

    public function isValidTm_zitudo(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_teizikan(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_zangyo(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_cyokka(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_zitudo_m(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_teizikan_m(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_zangyo_m(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_cyokka_m(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0.0);
    }

    public function isValidTm_hoyu_cyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_yosan(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidYn_soneki(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }
}
