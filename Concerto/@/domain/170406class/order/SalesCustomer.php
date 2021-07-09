<?php

/**
*   SalesCustomer
*
*   @version 170308
*/

namespace Concerto\domain\order;

use Concerto\domain\common\EntityObject;
use Concerto\domain\customer\CustomerId;
use Concerto\domain\customer\CustomerOrderId;
use Concerto\domain\contact\PostalAddress;

class SalesCustomer extends EntityObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = [
        'customerId',   //CustomerId
        'customerOrderId',  //CustomerOederId
        'receiver',
        'destinationAddress',   //PostalAddress
        'destinationTelephone', //TelephoneNumber
    ];

    /**
    *   {inherit}
    *
    */
    public function getId()
    {
        return $this->customerId->getId();
    }

    /**
    *   {inherit}
    *
    */
    public function equales()
    {
        return $this->customerId->equales();
    }

    public function isValidCustomerId($val)
    {
        if (!$val instanceof CustomerId) {
            return false;
        }
        return $this->customerId->isValid();
    }

    public function isValidCustomerOrderId($val)
    {
        if (!$val instanceof CustomerOrderId) {
            return false;
        }
        return $this->customerOrderId->isValid();
    }

    public function isValidReciever($val)
    {
        return Validate::isText($val, 0, 100);
    }

    public function isValidDestinationAddress($val)
    {
        if (!$val instanceof PostalAddress) {
            return false;
        }
        return $this->destinationAddress->isValid();
    }

    public function isValidDestinationTelephone($val)
    {
        if (!$val instanceof TelephoneNumber) {
            return false;
        }
        return $this->destinationTelephone->isValid();
    }
}
