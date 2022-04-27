<?php

/**
*   cyunyu_keikaku
*
*   @version 160818
*/

declare(strict_types=1);

namespace Concerto\database;

use Exception;
use finfo;
use InvalidArgumentException;
use PDO;
use RuntimeException;
use Concerto\standard\ModelDb;

class CyunyuKeikaku extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'tmp.cyunyu_keikaku';
}
