<?php

/**
*   claim_doc
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class ClaimDoc extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.claim_doc';
}
