<?php

/**
*   koban_tyousei
*
*   @version 160729
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class KobanTyousei extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.koban_tyousei';
}
