<?php

/**
*   project_cyuban
*
*   @version 150427
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class ProjectCyuban extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.project_cyuban';
}
