<?php

/**
*   EmailAddress
*
*   @version 180810
*/

namespace dev\domain\contact;

class EmailAddress
{
    /**
    *   address
    *
    *   @var string
    */
    protected $address;

    /**
    *   __construct
    *
    *   @param string
    */
    public function __construct($address)
    {
        $this->address = $address;
    }
}
