<?php

/**
*   Sigmagrid FilterInfo
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridFilterInfo extends DataContainerValidatable
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'fieldName', 'value', 'logic', 'columnId'
    ];

    /**
    *   __construct
    *   @param mixed[] $params
    */
    public function __construct(
        array $params = []
    ) {
        $this->fromArray($params);
    }

    public function isValidFieldName(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 1);
    }

    public function isValidValue(
        mixed $val
    ): bool {
        return true;
    }

    public function isValidLogic(
        mixed $val
    ): bool {
        $values = [
            'equal',
            'notEqual',
            'less',
            'great',
            'lessEqual',
            'greatEqual',
            'like',
            'startWith',
            'endWith'
        ];
        return in_array($val, $values);
    }

    public function isValidColumnId(
        mixed $val
    ): bool {
        if (is_null($val)) {
            return true;
        }
        return Validate::isAscii($val, 1);
    }
}
