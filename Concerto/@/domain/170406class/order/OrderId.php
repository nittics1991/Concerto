<?php

/**
*   OrderId
*
*   @version 170308
*/

namespace Concerto\domain\order;

use Concerto\domain\common\EntityObject;

class OrderId extends EntityObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['id', 'name', 'location'];

    public function isValidId($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 100);
    }

    public function isValidLocation($val)
    {
        return Validate::isText($val, 0, 100);
    }
}
