<?php

/**
*   Sigmagrid ColumnInfo
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridColumnInfo extends DataContainerValidatable
{
    /**
    *   Columns
    *
    *   @var string[]
    */
    protected static $schema = [
        'id', 'header', 'fieldName', 'fieldIndex','sortOrder',
        'hidden', 'exportable', 'printable'
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

    public function isValidId($val)
    {
        return Validate::isAscii($val, 1);
    }

    public function isValidHeader($val)
    {
        return
            Validate::isTextEscape($val, 0, 100, null, '\r\n\t') &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidFieldName($val)
    {
        return Validate::isAscii($val, 1);
    }

    public function isValidFieldIndex($val)
    {
        return Validate::isInt($val, 0) ||
            $this->isValidId($val) ;
    }

    public function isValidSortOrder($val)
    {
        if (is_null($val) || ($val == 'asc') || ($val == 'desc')) {
            return true;
        }
        return false;
    }

    public function isValidHidden($val)
    {
        return is_bool($val);
    }

    public function isValidExportable($val)
    {
        return is_bool($val);
    }

    public function isValidPrintable($val)
    {
        return is_bool($val);
    }
}
