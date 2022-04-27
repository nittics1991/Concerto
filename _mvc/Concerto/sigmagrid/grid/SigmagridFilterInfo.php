<?php

/**
*   Sigmagrid FilterInfo
*
*   @version 191125
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridFilterInfo extends DataContainerValidatable
{
    /**
    *   Columns
    *
    *   @var string[]
    */
    protected static $schema = [
        'fieldName', 'value', 'logic', 'columnId'
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(array $params = [])
    {
        $this->fromArray($params);
    }

    public function isValidFieldName($val)
    {
        return Validate::isAscii($val, 1);
    }

    public function isValidValue($val)
    {
        return true;
    }

    public function isValidLogic($val)
    {
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

    public function isValidColumnId($val)
    {
        if (is_null($val)) {
            return true;
        }
        return Validate::isAscii($val, 1);
    }
}
