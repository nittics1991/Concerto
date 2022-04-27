<?php

/**
*   OrderDetailId
*
*   @version 170308
*/

namespace dev\domain\order;

use dev\domain\common\EntityObject;

class OrderDetailId extends EntityObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['id', 'name', 'parent'];

    /**
    *   {inherit}
    *
    */
    public function getId()
    {
        return "{$this->parent}{$this->id}";
    }

    public function isValidId($val)
    {
        return Validate::isKoban($val);
    }

    public function isValidName($val)
    {
        return Validate::isText($val, 0, 100);
    }

    public function isValidParent($val)
    {
        return Validate::isCyuban($val);
    }
}
