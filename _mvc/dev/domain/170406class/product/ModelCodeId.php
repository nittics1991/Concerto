<?php

/**
*   ModelCodeId
*
*   @version 170308
*/

namespace dev\domain\order;

use dev\domain\common\EntityObject;

class ModelCodeId extends EntityObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['id', 'name'];

    public function isValidId($val)
    {
        return Validate::isModelCode($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 20);
    }
}
