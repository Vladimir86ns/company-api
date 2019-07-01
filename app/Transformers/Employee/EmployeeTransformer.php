<?php

namespace App\Transformers\Employee;

use App\Employee;
use League\Fractal\TransformerAbstract;

class EmployeeTransformer extends TransformerAbstract
{
    public function transform(Employee $employee)
    {
        return [
            'id' => $employee->id,
            'first_name' => $employee->userInfo->first_name,
            'last_name' => $employee->userInfo->last_name,
            'email' => $employee->email,
            'country' => $employee->userInfo->country,
            'city' => $employee->userInfo->city,
            'address' => $employee->userInfo->address,
            'phone_number' => $employee->userInfo->phone_number,
            'mobile_phone' => $employee->userInfo->mobile_phone,
            'employee_company_id' => $employee->employee_company_id,
            'hire_date' => $employee->hire_date,
            'company_id' => $employee->company_id,
        ];
    }
}


