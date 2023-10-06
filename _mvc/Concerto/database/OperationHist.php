<?php

/**
*   operation_hist
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class OperationHist extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.operation_hist';
}
