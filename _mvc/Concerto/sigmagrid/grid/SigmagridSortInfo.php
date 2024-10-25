<?php

/**
*   Sigmagrid SortInfo
*
*   @version 220615
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

/**
*   @template TValue
*   @extends DataContainerValidatable<TValue>
*/
class SigmagridSortInfo extends DataContainerValidatable
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'columnId', 'fieldName',
        'sortOrder', 'getSortValue',
        'sortFn'
    ];

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

    public function isValidColumnId(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 1);
    }

    public function isValidFieldName(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 1);
    }

    public function isValidSortOrder(
        mixed $val
    ): bool {
        if (
            is_null($val) ||
            $val === 'asc' ||
            $val === 'desc'
        ) {
            return true;
        }
        return false;
    }

    public function isValidGetSortValue(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }

    public function isValidSortFn(
        mixed $val
    ): bool {
        return Validate::isText($val, 0);
    }
}
