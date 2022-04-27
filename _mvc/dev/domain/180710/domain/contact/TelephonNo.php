<?php

/**
*   TelephonNo
*
*   @version 180810
*/

namespace dev\domain\contact;

class TelephonNo
{
    /**
    *   no
    *
    *   @var string
    */
    protected $no;

    /**
    *   __construct
    *
    *   @param string
    */
    public function __construct($no)
    {
        $this->no = $no;
    }
}
