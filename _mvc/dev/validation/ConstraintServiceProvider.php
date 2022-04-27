<?php

/**
*   ConstraintServiceProvider
*
*   @version 180613
*/

declare(strict_types=1);

namespace dev\validation;

use dev\container\provider\AbstractDirectoryServiceProvider;

class ConstraintServiceProvider extends AbstractDirectoryServiceProvider
{
    /**
    *   {inherit}
    *
    */
    protected $subDirName = 'constraint';
    protected $prefixId = 'validation';
}
