<?php

namespace App\Services\Company;

use App\Company;
use App\User;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    /**
     * Get company.
     *
     * @param string $id company id
     * @param string $accountId account id
     * @return Company;
     */
    public function getCompanyByIdAndAccountId(string $id, string $accountId)
    {
        return Company::where([['id', $id], ['account_id', $accountId]])->first();
    }

    /**
     * Create company.
     *
     * @param User $user
     * @param array $attributes
     * @return Company;
     */
    public function createCompany(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            return Company::create($attributes);
        });
    }

    /**
     * Update company.
     *
     * @param Company $company
     * @param array   $attributes
     *
     * @return mixed
     */
    public function update(Company $company, array $attributes)
    {
        return DB::transaction(function () use ($company, $attributes) {
            $company->update($attributes);
            return $company;
        });
    }
}
