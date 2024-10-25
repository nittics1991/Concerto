<?php

/**
*   doc_inf
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
class DocInfData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'ins_date' => parent::STRING,
        'cd_kbn_01' => parent::STRING,
        'cd_kbn_02' => parent::STRING,
        'nm_doc' => parent::STRING,
        'nm_doc_inf' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'nm_upfile_url' => parent::STRING,
        'nm_upfile_dir  ' => parent::STRING,
    ];

    public function isValidIns_date(
        mixed $val
    ): bool {
        return is_string($val) &&
            mb_ereg_match(
                '\A\d{4}/\d{2}/\d{2}_\d{2}:\d{2}:\d{2}(\.\d*)*\z',
                $val
            );
    }

    public function isValidCd_kbn_01(
        mixed $val
    ): bool {
        if (!is_null($val)) {
            return Validate::isTextInt($val);
        }
        return true;
    }

    public function isValidCd_kbn_02(
        mixed $val
    ): bool {
        if (!is_null($val)) {
            return Validate::isTextInt($val);
        }
        return true;
    }

    public function isValidNm_doc(
        mixed $val
    ): bool {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }

    public function isValidNm_doc_inf(
        mixed $val
    ): bool {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidNm_upfile_url(
        mixed $val
    ): bool {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }

    public function isValidNm_upfile_dir(
        mixed $val
    ): bool {
        if (!is_null($val)) {
            return Validate::isText($val);
        }
        return true;
    }
}
