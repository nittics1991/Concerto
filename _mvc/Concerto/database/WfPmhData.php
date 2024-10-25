<?php

/**
*   wf_pmh
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
class WfPmhData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'cd_tanto' => parent::STRING,
        'dt_kakunin' => parent::STRING,
    ];

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }

    public function isValidNo_page(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
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
