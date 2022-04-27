<?php

/**
*   ApprovalUsers
*
*   @version 170308
*/

namespace dev\domain\contact;

use dev\domain\common\ValueObject;

class ApprovalUsers extends ValueObject
{
    /**
    *   {inherit}
    *
    */
    protected static $properties = [
        'approvalByDesigner',
        'approvalByInvestigator',
        'approvalByAuthorizer'
    ];

    public function isValidId($val)
    {
        return Validate::isTanto($val);
    }

    public function isValidApprovedByDesigner($val)
    {
        return $this->approvalByDesigner->isValid();
    }

    public function isValidApprovedByInvestigator($val)
    {
        return $this->approvalByInvestigator->isValid();
    }

    public function isValidApprovedByAuthorizer($val)
    {
        return $this->approvalByAuthorizer->isValid();
    }
}
