<?php

class CustomerOrderId extends Entity
    $id










class Employee extends Entity
    EmployeeId $employeeId
    string $EmployeeName
    setFilterName() //区切り空白調整
    Date $startDate
    Date $endDate

class Certificate extends ValueObject
    string $password
    DateTime $updatedAt

class UnifiedUserId extends Entity
    $id

class EmployeeEmailAddress extends EmailAddress
    $emailAddress

class EmployeeAuthorityLevel extends Enum
    REGULAR
    SUBMANAGER
    MANAGER

//namespace costdepartment
class CostDepartmentId
    $id

class RegisterEmployeeUseCase extends Entity
    Employee $employee
    string $employeeIndicator
    Certificate $certificate
    UnifiedUserId $unifiedUserId
    CostDepartmentId $costDepartmentId
    EmployeeEmailAddress $employeeEmailAddress
    EmployeeAuthorityLevel $employeeAuthorityLevel
    Percent $laborCostRate
    bool $masterOperator
    bool $useCookie
    bool $useEmail
