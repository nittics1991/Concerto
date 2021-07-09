<?php

/**
*
*/

declare(strict_types=1);

namespace Concerto\test;

use PDO;

trait TestPdoConnectionTrait
{
    protected function getTestConnection(): ?PDO
    {
        //development environment confirmation
        if (!preg_match('/543[0,4,6]/', $GLOBALS['DB_DSN'])) {
            die("development environment confirmation error:DNS={$GLOBALS['DB_DSN']}");
        }

        return new PDO(
            $GLOBALS['DB_DSN'],
            $GLOBALS['DB_USER'],
            $GLOBALS['DB_PASSWD']
        );
    }
}
