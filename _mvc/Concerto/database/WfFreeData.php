<?php

/**
*   wf_free
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
class WfFreeData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_cyu' => parent::STRING,
        'no_page' => parent::INTEGER,
        'no_seq' => parent::INTEGER,
        'cd_elem' => parent::STRING,
        'cd_plan' => parent::STRING,
        'cd_real' => parent::STRING,
        'cd_job' => parent::STRING,
        'nm_job' => parent::STRING,
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

    public function isValidNo_rev(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidNo_seq(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidCd_elem(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }

    //cd_plan
    //cd_real

    public function isValidCd_job(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }

    public function isValidNm_job(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
