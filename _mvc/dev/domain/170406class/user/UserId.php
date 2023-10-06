<?php

/**
*   UserId
*
*   @version 170308
*/

namespace dev\domain\user;

use dev\domain\common\EntityObject;

class UserId extends EntityObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['id', 'name', 'pseudonymReading'];

    public function isValidId($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 20);
    }

    public function isValidPseudonymReading($val)
    {
        return Validate::isText($val, 0, 20);
    }
}
