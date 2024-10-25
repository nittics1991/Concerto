<?php

/**
*   PostalAddress
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class PostalAddress extends ValueObject
{
    /**
    *   @inheritDoc
    *
    */
    protected static $properties = ['no', 'country', 'region', 'locality', 'street', 'extended'];

    /**
    *   getAddress
    *
    *   @return string
    */
    public function getAddress()
    {
        return "{$region}{$locality}{$street}{$extended}";
    }

    public function isValidNo($val)
    {
        return Validate::isPostAddress($val);
    }
}
