<?php

/**
*   mitumori_koban
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Concerto\standard\ModelDb;

class MitumoriKoban extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mitumori_koban';
}
