<?php

/**
*   wf_syukka
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class WfSyukka extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.wf_syukka';
}
