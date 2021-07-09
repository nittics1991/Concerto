<?php

/**
*   RespectConverterServiceProvider
*
*   @version 180614
*/

declare(strict_types=1);

namespace Concerto\validation\respect;

use Concerto\container\provider\AbstractDirectoryServiceProvider;

class RespectConverterServiceProvider extends AbstractDirectoryServiceProvider
{
    /**
    *   {inherit}
    *
    */
    protected $subDirName = 'converter';
    protected $prefixId = 'converter';
}
