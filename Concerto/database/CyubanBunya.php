<?php

/**
*   cyuban_bunya
*
*   @version 180403
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class CyubanBunya extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.cyuban_bunya';
}
