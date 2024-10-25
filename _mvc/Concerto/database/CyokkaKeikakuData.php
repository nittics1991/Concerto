<?php

/**
*   cyokka_keikaku
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
class CyokkaKeikakuData extends ModelData
{
    /**
    *   @var float
    */
    public const TEIJIKAN = 7.75;

    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        "kb_nendo" => parent::STRING
        , "cd_bumon" => parent::STRING
        , "su_cyokka" => parent::INTEGER
        , "ri_cyokka" => parent::INTEGER
        , "yn_tanka" => parent::INTEGER
        , "tm_zangyo_m" => parent::DOUBLE
        , "ri_syukkin" => parent::INTEGER
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

    //cd_bumon_dmy

    public function isValidSu_cyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidRi_cyokka(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 100);
    }

    public function isValidYn_tanka(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidTm_zangyo_m(
        mixed $val
    ): bool {
        return Validate::isDouble($val, 0);
    }

    public function isValidRi_syukkin(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0, 100);
    }
}
