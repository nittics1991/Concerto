<?php

/**
*   PostalAddress
*
*   @version 170308
*/

namespace Concerto\domain\contact;

use Concerto\domain\common\ValueObject;

class PostalAddress extends ValueObject
{
    /**
    *   {inherit}
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
