<?php

class CostDepartmentId extends Entity
    $id

class CostDepartment extends Entity
    CostDepartmentId $costDepartmentId
    string name
    Date $startDate
    Date $endDate

//経歴の関係
class CostDepartmentCareer
    CostDepartmentId $
    CostDepartmentId $ancestor

//collectionを用いるか?
class CostDepartmentCareerCollection extends Collection



class RegisterEmployeeUseCase extends Entity
    CostDepartment $costDepartment
    array $careers  //CostDepartmentCareerCollection
    bool $costManagement
    bool $scheduleManagement
    bool $costomerOrderManagement

//BUグループが必要になるかも
