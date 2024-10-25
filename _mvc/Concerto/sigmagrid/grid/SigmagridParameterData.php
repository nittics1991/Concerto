<?php

/**
*   SigmagridParameterData
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\auth\Csrf;
use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

/**
*   @template TValue
*   @extends DataContainerValidatable<TValue>
*/
class SigmagridParameterData extends DataContainerValidatable
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = ['token'];

    /**
    *   __construct
    *
    *   @param array<bool|int|float|string|null> $params
    */
    public function __construct(
        array $params = []
    ) {
        $this->fromArray($params);
    }

    public function isValidToken(
        mixed $val
    ): bool {
        return Csrf::isValid(strval($val), false);
    }
}
