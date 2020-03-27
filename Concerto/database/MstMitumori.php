<?php

/**
*   mst_mitumori
*
*   @version 180530
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MstMitumori extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mst_mitumori';
}
