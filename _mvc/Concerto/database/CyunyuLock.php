<?php

/**
*   cyunyu_lock
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class CyunyuLock extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.cyunyu_lock';
}
