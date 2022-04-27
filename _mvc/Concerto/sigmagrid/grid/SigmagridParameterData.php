<?php

/**
*   SigmagridParameterData
*
*   @version 170425
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\auth\Csrf;
use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridParameterData extends DataContainerValidatable
{
    /**
    *   Columns(over write)
    *
    *   @var string[]
    */
    protected static $schema = ['token'];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(array $params = [])
    {
        $this->fromArray($params);
    }

    public function isValidToken($val)
    {
        return Csrf::isValid($val, false);
    }
}
