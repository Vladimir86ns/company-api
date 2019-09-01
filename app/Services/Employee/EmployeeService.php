<?php

namespace App\Services\Employee;


use App\Company;
use App\Employee;
use App\Services\Utils\UtilsService;
use App\UserInfo;
use Illuminate\Support\Arr;
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
            $userInfo = UserInfo::create($attributes);
            $userInfo->employee()->create($attributes);

            return $userInfo->employee;
        });
    }

    /**
     * Update employee info and user info.
     *
     * @param array    $attributes
     * @param Employee $employee
     *
     * @return mixed
     */
    public function updateEmployee(array $attributes, Employee $employee)
    {
        return DB::transaction(function () use ($attributes, $employee) {
            $filtered = Arr::except($attributes, ['account_id', 'company_id', 'id']);
            $employee->update($filtered);
            $employee->userInfo()->update($filtered);

            return $employee;
        });
    }

    /**
     * Get employee by id and company id.
     *
     * @param string $id
     * @param string $companyID
     *ap
     * @return mixed
     */
    public function getEmployeeByIdAndCompanyId(string $id, string $companyID)
    {
        return Employee::where(['id' => $id, 'company_id' => $companyID])->first();
    }

    /**
     * Get all employees by company id.
     *
     * @param string $companyID
     *
     * @return mixed
     */
    public function getEmployeesByCompanyId(string $companyID)
    {
        return Employee::where('company_id', $companyID)->paginate(10);
    }

    /**
     * Get recommended id for create employee.
     *
     * @param int $companyID
     *
     * @return mixed
     */
    public function getRecommendedID(int $companyID)
    {
        $existingIds = Employee::where('company_id', $companyID)
            ->withTrashed()
            ->pluck('employee_company_id')
            ->toArray();

        $company = Company::where('id', $companyID)->first();

        return $this->getId(
            $existingIds,
            $company->employee_id_prefix,
            count($existingIds) + 1
        );
    }

    /**
     * Prepare id and check does exist.
     *
     * @param array        $existingIds
     * @param mix       $companyShortName
     * @param int          $countIds
     * @param UtilsService $utilsService
     *
     * @return string
     */
    public function getId(
        array $existingIds,
        $companyShortName,
        int $countIds
    ) {
        $utilsService = new UtilsService();

        if ($companyShortName) {
            $testId = $utilsService->getPreparedID($countIds, $companyShortName . '-');
        } else {
            $testId = $utilsService->getPreparedID($countIds, '');
        }

        if (in_array($testId, $existingIds)) {
            $testId = $this->getId(
                $existingIds,
                $companyShortName,
                $countIds + 1
            );
        }

        return $testId;
    }
}
