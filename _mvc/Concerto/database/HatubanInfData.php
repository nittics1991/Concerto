<?php

/**
*   hatuban_inf
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
class HatubanInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        "no_cyu" => parent::STRING
        , "dt_hatuban" => parent::STRING
        , "cd_tanto" => parent::STRING
        , "dt_kakunin" => parent::STRING
    ];

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidDt_hatuban(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidDt_kakunin(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }
}
