<?php

/**
*   claim_old
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class ClaimOld extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.claim_old';
}
