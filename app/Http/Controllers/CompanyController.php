<?php

namespace App\Http\Controllers;

use App\Transformers\Company\CompanyTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateCreateCompany;
use App\Validators\CompanyValidator;
use App\Validators\UserValidator;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Company\CompanyService;

/**
 * Class CompanyController
 *
 * @package App\Http\Controllers
 */
class CompanyController extends Controller
{
    /**
     * @var \App\Services\Company\CompanyService
     */
    protected $service;

    /**
     * @var \App\Validators\UserValidator
     */
    protected $userValidator;

    /**d
     * @var \App\Validators\CompanyValidator
     */
    protected $validator;

    /**
     * @var \App\Transformers\Company\CompanyTransformer
     */
    protected $transformer;

    /**
     * CompanyController constructor.
     *
     * @param CompanyValidator $companyValidator
     * @param UserValidator    $userValidator
     * @param CompanyService   $companyService
     * @param CompanyTransformer $companyTransformer
     */
    public function __construct(
        CompanyValidator $companyValidator,
        UserValidator $userValidator,
        CompanyService $companyService,
        CompanyTransformer $companyTransformer
    ) {
        $this->validator = $companyValidator;
        $this->userValidator = $userValidator;
        $this->service = $companyService;
        $this->transformer = $companyTransformer;
    }

    /**
     * Get company by id and account id.
     *
     * @param string $id
     * @param string $accountId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getCompanyByIdAndAccountId(string $id, string $accountId)
    {
        $company = $this->validator->getAndValidateCompanyAndAccountId($id, $accountId);

        return response($this->transformer->transform($company), Response::HTTP_OK);
    }


    /**
     * Create new company.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $inputs = $request->all();
        $errors = $this->validator->userValidator($inputs, new ValidateCreateCompany($inputs));

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $user = $this->userValidator->getAndValidateUserAndAccountId($inputs['user_id'], $inputs['account_id']);

        try {
            $company = $this->service->createCompany($inputs);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Something went wrong, try again later!');
        }

        if (!$user->account->company_settings_done) {
            $user->account->update(['company_settings_done' => 1, 'headquarter_company_id' => $company->id]);
        }

        return response($this->transformer->transform($company), Response::HTTP_OK);
    }
}
