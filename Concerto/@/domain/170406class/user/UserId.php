<?php

/**
*   UserId
*
*   @version 170308
*/

namespace Concerto\domain\user;

use Concerto\domain\common\EntityObject;

class UserId extends EntityObject
{
    /**
    *   {inherit}
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
