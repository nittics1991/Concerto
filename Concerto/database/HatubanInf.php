<?php

/**
*   hatuban_inf
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class HatubanInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.hatuban_inf';
}
