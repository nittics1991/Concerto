<?php

/**
*   RespectConverterServiceProvider
*
*   @version 180614
*/

declare(strict_types=1);

namespace dev\validation\respect;

use dev\container\provider\AbstractDirectoryServiceProvider;

class RespectConverterServiceProvider extends AbstractDirectoryServiceProvider
{
    /**
    *   @inheritDoc
    *
    */
    protected $subDirName = 'converter';
    protected $prefixId = 'converter';
}
