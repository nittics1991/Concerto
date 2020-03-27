<?php

/**
*   claim_sonhi
*
*   @version 200107
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class ClaimSonhi extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.claim_sonhi';
}
