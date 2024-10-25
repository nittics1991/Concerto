<?php

/**
*   Sigmagrid ColumnInfo
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
class SigmagridColumnInfo extends DataContainerValidatable
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'id', 'header', 'fieldName', 'fieldIndex','sortOrder',
        'hidden', 'exportable', 'printable'
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

    public function isValidId(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 1);
    }

    public function isValidHeader(
        mixed $val
    ): bool {
        return
            Validate::isTextEscape($val, 0, 100, null, '\r\n\t') &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidFieldName(
        mixed $val
    ): bool {
        return Validate::isAscii($val, 1);
    }

    public function isValidFieldIndex(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0) ||
            $this->isValidId($val) ;
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

    public function isValidHidden(
        mixed $val
    ): bool {
        return is_bool($val);
    }

    public function isValidExportable(
        mixed $val
    ): bool {
        return is_bool($val);
    }

    public function isValidPrintable(
        mixed $val
    ): bool {
        return is_bool($val);
    }
}
