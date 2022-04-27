<?php

/**
*   project_cyuban
*
*   @version 210119
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelData;
use Concerto\Validate;

class ProjectCyubanData extends ModelData
{
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = [
        'no_project' => parent::INTEGER,
        'no_cyu' => parent::STRING,
    ];

    public function isValidNo_project($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidNo_cyu($val)
    {
        return Validate::isCyuban($val);
    }
}
