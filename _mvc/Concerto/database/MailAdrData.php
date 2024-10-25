<?php

/**
*   mail_adr
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
class MailAdrData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'update' => parent::STRING,
        'editor' => parent::STRING,
        'cd_tanto' => parent::STRING,
        'cd_adr' => parent::STRING,
    ];


    public function isValidUpdate(
        mixed $val
    ): bool {
        return Validate::isTextDate($val);
    }

    public function isValidEditor(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidCd_tanto(
        mixed $val
    ): bool {
        return Validate::isTanto($val);
    }

    public function isValidCd_adr(
        mixed $val
    ): bool {
        return Validate::isText($val);
    }
}
