<?php

/**
*   RespectConstraintServiceProvider
*
*   @version 180613
*   @caution 本ファイルはRespect\Validationに設置する
*/

declare(strict_types=1);

namespace Respect\Validation;

use dev\container\provider\AbstractDirectoryServiceProvider;

class RespectConstraintServiceProvider extends AbstractDirectoryServiceProvider
{
    /**
    *   @inheritDoc
    *
    */
    protected $subDirName = 'Rules';
    protected $prefixId = 'validation';
}
