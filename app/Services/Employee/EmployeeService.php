<?php

namespace App\Services\Employee;


use App\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * Create employee.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function createEmployee(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $employee = Employee::create($attributes);
            $employee->userInfo()->create($attributes);

            return $employee;
        });
    }
}
