<?php

/**
*   QuotationId
*
*   @version 170308
*/

namespace Concerto\domain\user;

use Concerto\domain\common\EntityObject;

class QuotationId extends EntityObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['id', 'name', 'location'];

    public function isValidId($val)
    {
        return Validate::isBumon($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 100);
    }

    public function isValidLOcation($val)
    {
        return Validate::isText($val, 0, 100);
    }
}
