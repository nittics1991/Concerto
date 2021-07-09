<?php

/**
*   MaterialCost
*
*   @version 170316
*/

namespace Concerto\domain\contact;

use Concerto\domain\common\ValueObject;

class MaterialCost extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['procurementId'];

    /**
    *   {inherit}
    *
    */
    protected $delegates = ['manufacturingCost'];

    /**
    *   {inherit}
    *
    */
    public function __construct($param)
    {
        $this->boot();
        parent::__construct($param);
    }

    protected function boot()
    {
        $this->delegates['manufacturingCost'] = new ManufacturingCost();
    }

    public function isValidProcurementId()
    {
        return $this->procurementId->isValid();
    }
}
