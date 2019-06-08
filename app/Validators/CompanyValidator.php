<?php

namespace App\Validators;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\Company\CompanyService;
use App\Company;
use Validator;

class CompanyValidator
{
    /**
     * @var \App\Services\Company\CompanyService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param CompanyService $companyService
     * @return void
     */
    public function __construct(
        CompanyService $companyService
    ) {
        $this->service = $companyService;
    }

    /**
     * Validate and return user if exist.
     *
     * @param string $companyId
     * @param string $accountId
     * @return Company;
     */
    public function getAndValidateCompanyAndAccountId(string $companyId, string $accountId)
    {
        $company = $this->service->getCompanyByIdAndAccountId($companyId, $accountId);

        if (!$company) {
            return abort(Response::HTTP_NOT_FOUND, 'Company not found!');
        }

        $this->validateAccountId($company, $accountId);

        return $company;
    }

    /**
     * Validate create user
     *
     * @param array $data
     * @param FormRequest $validator
     * @return mixed
     */
    public function userValidator(array $data, FormRequest $validator)
    {
        return $this->validateData($data, $validator);
    }

    /**
     * Validate data
     *
     * @param array $data
     * @param FormRequest $validator
     * @return array
     */
    public function validateData(array $data, FormRequest $validator)
    {
        $validator = Validator::make($data, $validator->rules(), $validator->messages());
        return $validator->fails() ? $validator->errors()->messages() : [];
    }

    /**
     * Validate data
     *
     * @param Company $user
     * @param string $accountId
     */
    private function validateAccountId(Company $company, string $accountId)
    {
        if ($company->account->id !== (int) $accountId) {
            return abort(Response::HTTP_NOT_ACCEPTABLE, "Company doesn't belong to a given account!");
        }
    }
}
