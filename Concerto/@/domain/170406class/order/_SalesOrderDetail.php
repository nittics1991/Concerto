<?php

/**
*   SalesOrderDetail
*
*   @version 170308
*/

namespace Concerto\domain\order;

class SalesOrderDetail
{
    OrderDetailId $orderDetailId;
    SalesCost $salesCost;
    DepertmentId $manifacturingDepertment;
    SalesDetailSchedule $salesDetailSchedule;
    ApprovalUsers $approvalUsers;
}


class SalesDetailSchedule
{
    $deliveryDate
    $dueDate
}
