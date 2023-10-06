<?php

/**
*   SeibanKanriDisp
*
*   @version 170309
*/

namespace dev\domain\usecase;

class SeibanKanriDisp
{
    ProjectId $projectId;
    OrderId $orderId;
    CustomerId $customerId;
    CustomerOrderId $customerOrderId;
    SalesPlice $salesPlice;

    //cost

    YearMonth $quotationSalesDate;
    UserId $Salesman;
    UserId $Engineeer;

    //発番日関係
    DateTimeObject $orderNumberingIsssueDate;
    DateTimeObject $orderNumberingUpdateDate;
    DateTimeObject $orderNumberConfirirmationDate;
    UserId $orderNumberConfirirmationUser;

    DateTimeObject $lastOccurredLaborCostDate

    //adjust cost
        $adjustCost
}
}
