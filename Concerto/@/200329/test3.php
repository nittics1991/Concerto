<?php

//社員Entity (MstTanto context)
class Employee extends AbstractEntity
{
    EmployeeNo $employeeNo;
    $employeeName;
    $unifiedUserId;
    $emailAddress;
    CostDepartment $costDepartment;
    
    
    
    //delegate ==> traitにできないか?
    
    public function euqals(EntityInterface $employee)
    {
        return $this->employeeNo->euqals($employee);
    }
    
    public function same(EntityInterface $employee)
    {
        return $this->employeeNo->same($employee);
    }
}

//社員番号Entity (other then MstTanto context)
class EmployeeNo extends AbstractEntity
{
}

abstract class AbstractEntity implement EntityInterface
{
    protected $uid;
    protected $id;
    
    public function __construct(
        RandomNumberGenaratorInterface $generator = null
    ) {
        $generator = $generator ?? new OpenSslRandomNumberGenarator();
        $this->uid = $generator->generate();
    }
    
    public function getUid()
    {
        return $this->uid;
    }
    
    public function getId()
    {
         return $this->id;
    }
    
    public function equals(EntityInterface $entity)
    {
        return $this->uid === $entity->getUid();
    }
    
    public function same(EntityInterface $entity)
    {
        return $this->id === $entity->getId();
    }
}

//標題
interface EntityInterface
{
}




//資格
class Credential
{
    $employee_no;
    $password;
    
}

//許諾
interface PermissionInterface
{
    isPermitted($code): bool
    
}

//社員許諾
class EmployeePermission implements PermissionInterface
{
    $permission_code;
    $permission_description;
    
}

//組織許諾
class DepartmentPermission
{
}

//組織
class Department
{
    $department_code;
    $department_name;
    DepartmentPermission[] $permissions;
    
}

class MstBumonData
{
    Employee $employee;
    Credential $certification;
    EmployeePermission[] $permissions;
    
}






