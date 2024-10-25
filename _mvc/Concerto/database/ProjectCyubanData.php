<?php

/**
*   project_cyuban
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
class ProjectCyubanData extends ModelData
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'no_project' => parent::INTEGER,
        'no_cyu' => parent::STRING,
    ];

    public function isValidNo_project(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidNo_cyu(
        mixed $val
    ): bool {
        return Validate::isCyuban($val);
    }
}
