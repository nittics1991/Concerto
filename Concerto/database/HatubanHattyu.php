<?php

/**
*   hatuban_hattyu
*
*   @version 180313
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class HatubanHattyu extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.hatuban_hattyu';
}
