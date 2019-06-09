<?php

namespace App\Http\Controllers;

use App\Transformers\User\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateUpdateUser;
use App\Validators\UserValidator;
use Symfony\Component\HttpFoundation\Response;
use App\Services\User\UserService;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @var \App\Services\User\UserService
     */
    protected $service;

    /**
     * @var \App\Validators\UserValidator
     */
    protected $validator;

    /**
     * @var \App\Transformers\User\UserTransformer
     */
    protected $transformer;

    /**
     * Create a new controller instance.
     *
     * @param UserValidator $userValidator
     * @param UserService $userService
     * @param UserTransformer $userTransformer
     *
     * @return void
     */
    public function __construct(
        UserValidator $userValidator,
        UserService $userService,
        UserTransformer $userTransformer
    ) {
        $this->validator = $userValidator;
        $this->service = $userService;
        $this->transformer = $userTransformer;
    }

    /**
     * Get user.
     *
     * @param string $id
     * @param string $accountId
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getUser(string $id, string $accountId)
    {
        $user = $this->validator->getAndValidateUserAndAccountId($id, $accountId);

        return response(
            $this->transformer->transform($user),
            Response::HTTP_OK
        );
    }


    /**
     * Update user.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $inputs = $request->all();
        $errors = $this->validator->userValidator($inputs, new ValidateUpdateUser($inputs));

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $user = $this->validator->getAndValidateUserAndAccountId($inputs['user_id'], $inputs['account_id']);

        try {
            $user = $this->service->updateUser($user, $inputs);
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Something went wrong, try again later!');
        }

        return response(
            $this->transformer->transform($user),
            Response::HTTP_OK
        );
    }
}
