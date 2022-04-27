<?php

/**
*   Sigmagrid SortInfo
*
*   @version 170424
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridSortInfo extends DataContainerValidatable
{
    /**
    *   Columns
    *
    *   @var string[]
    */
    protected static $schema = [
        'columnId', 'fieldName', 'sortOrder', 'getSortValue', 'sortFn'
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

    public function isValidColumnId($val)
    {
        return Validate::isAscii($val, 1);
    }

    public function isValidFieldName($val)
    {
        return Validate::isAscii($val, 1);
    }

    public function isValidSortOrder($val)
    {
        if (is_null($val) || ($val == 'asc') || ($val == 'desc')) {
            return true;
        }
        return false;
    }

    public function isValidGetSortValue($val)
    {
        return Validate::isText($val, 0);
    }

    public function isValidSortFn($val)
    {
        return Validate::isText($val, 0);
    }
}
