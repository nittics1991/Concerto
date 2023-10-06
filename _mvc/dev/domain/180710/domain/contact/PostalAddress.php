<?php

/**
*   PostalAddress
*
*   @version 180810
*/

namespace dev\domain\contact;

class PostalAddress
{
    /**
    *   zip
    *
    *   @var string
    */
    protected $zip;

    /**
    *   address
    *
    *   @var string
    */
    protected $address;

    /**
    *   country
    *
    *   @var string
    */
    protected $country;

    /**
    *   __construct
    *
    *   @param string
    *   @param string
    *   @param string|null
    */
    public function __construct($zip, $address, $country = null)
    {
        $this->zip = $zip;
        $this->address = $address;
        $this->country = $country;
    }
}
