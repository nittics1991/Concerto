<?php
//in memory object で考えてみる

//ストーリーからclassを考える



///////////////////////////////////////////////////

//@sample Centinel

//形容詞　許可されたXXX
interface PermissibleInterface
{
    public function getPermissionsInstance(): PermissionsInterface;
    public function addPermission(string $permission, bool $value = true): PermissibleInterface;
    public function updatePermission(string $permission, bool $value = true, bool $create = false): PermissibleInterface;
    
}

//集合(table)
//名詞　許可　複数形
interface PermissionsInterface
{
    public function hasAccess($permissions): bool;
    public function hasAnyAccess($permissions): bool;
}





///////////////////////////////////////////////////
///////////////////////////////////////////////////

//社員は

// 資格でパスワードで認証する
//  private? 認証済みであるか

//資格を持っているか




///////////////////////////////////////////////////

//current usecase

class MstTanto
{
    
}

//社員
class Employee
{
    $employee_no;
    $employee_name;
    $unified_user_id;
    $email_address;
    CostDepartment $cost_department;
    
}

class EmployeeNo
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






