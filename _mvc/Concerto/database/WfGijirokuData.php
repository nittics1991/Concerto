<?php

/**
*   wf_gijiroku
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
class WfGijirokuData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'cd_type' => parent::STRING,
        'dt_kaisai' => parent::STRING,
        'cd_rank' => parent::STRING,
        'nm_basyo' => parent::STRING,
        'nm_syusseki' => parent::STRING,
        'nm_singi' => parent::STRING,
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

    public function isValidCd_type(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidDt_kaisai(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidCd_rank(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_basyo(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_syusseki(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    public function isValidNm_singi(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
