<?php

/**
*   operation_hist
*
*   @version 150419
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class OperationHist extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.operation_hist';
}
