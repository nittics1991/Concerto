<?php

/**
*   ApprovalUser
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class ApprovalUser extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = ['id', 'depertmentId', 'approvedDate'];

    public function isValidId($val)
    {
        return $this->id->isValid();
    }

    public function isValidDepertmentId($val)
    {
        return $this->depertmentId->isValid();
    }

    public function isValidApprovedDate($val)
    {
        return Validate::isTextDate($val);
    }
}
