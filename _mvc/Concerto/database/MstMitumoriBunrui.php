<?php

/**
*   mst_mitumori_bunrui
*
*   @version 180308
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstMitumoriBunrui extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_mitumori_bunrui';
}
