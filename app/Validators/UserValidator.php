<?php

namespace App\Validators;

use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Services\User\UserService;
use Illuminate\Foundation\Http\FormRequest;
use Validator;

class UserValidator
{
    /**
     * @var \App\Services\User\UserService
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @param UserService $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * Validate and return user if exist.
     *
     * @param string $userId
     * @return User;
     */
    public function getAndValidateUserAndAccountId(string $userId, string $accountId)
    {
        $user = $this->service->getUserById($userId);
        if (!$user) {
            return abort(Response::HTTP_NOT_FOUND, 'User not found!');
        }

        $this->validateAccountId($user, $accountId);

        return $user;
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
     * @param User $user
     * @param string $accountId
     */
    private function validateAccountId(User $user, string $accountId)
    {
        if ($user->account->id !== (int) $accountId) {
            return abort(Response::HTTP_NOT_ACCEPTABLE, "User doesn't belong to a given account!");
        }
    }
}
