<?php

/**
*   cyuban_furikae
*
*   @version 171201
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;

class CyubanFurikae extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.cyuban_furikae';
}
