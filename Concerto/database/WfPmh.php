<?php

/**
*   wf_pmh
*
*   @version 170522
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;
use Concerto\standard\ModelData;

class WfPmh extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.wf_pmh';
}
