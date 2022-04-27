<?php

/**
*   mitumori_koban
*
*   @version 180525
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MitumoriKoban extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mitumori_koban';
}
