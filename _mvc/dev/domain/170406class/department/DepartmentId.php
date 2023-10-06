<?php

/**
*   DepartmentId
*
*   @version 170314
*/

namespace dev\domain\department;

use dev\domain\common\EntityObject;

class DepartmentId extends EntityObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['id', 'name'];

    public function isValidId($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 20);
    }
}
