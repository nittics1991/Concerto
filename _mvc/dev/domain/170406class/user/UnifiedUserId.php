<?php

/**
*   UnifiedUserId
*
*   @version 170314
*/

namespace dev\domain\user;

use dev\domain\common\EntityObject;

class UnifiedUserId extends EntityObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['id'];

    public function isValidId($val)
    {
        return Validate::isUser($val);
    }
}
