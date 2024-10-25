<?php

/**
*   cyunyu_lock
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class CyunyuLock extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.cyunyu_lock';
}
