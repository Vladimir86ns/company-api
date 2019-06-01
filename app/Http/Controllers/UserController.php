<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateUpdateUser;
use App\Validators\UserValidator;
use Symfony\Component\HttpFoundation\Response;
use App\Services\User\UserService;
use App\User;

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
     * Create a new controller instance.
     *
     * @param UserValidator $userValidator
     * @param UserService $userService
     * @return void
     */
    public function __construct(
        UserValidator $userValidator,
        UserService $userService
    ) {
        $this->validator = $userValidator;
        $this->service = $userService;
    }

    /**
     * Update user.
     *
     * @param Request $request
     * @return void
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
            // TODO change status code.
            \Log::info($e->getMessage());
            abort(Response::HTTP_NOT_ACCEPTABLE, 'Something went wrong, try again later!');
        }

        // TODO return from transformer
        return response([
            'email' => $user->email,
            'first_name' => $user->userInfo->first_name,
            'last_name' => $user->userInfo->last_name,
            'country' => $user->userInfo->country,
            'city' => $user->userInfo->city,
            'address' => $user->userInfo->address,
            'phone_number' => $user->userInfo->phone_number,
            'mobile_phone' => $user->userInfo->mobile_phone,
            'company_settings_done' => $user->account->company_settings_done,
            'user_settings_done' => $user->account->user_settings_done,
        ], Response::HTTP_OK);
    }
}