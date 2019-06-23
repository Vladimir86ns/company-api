<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateCreateUser;
use App\Validators\UserValidator;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * @var \App\Validators\UserValidator
     */
    protected $validator;

    /**
     * Create a new controller instance.
     *
     * @param UserValidator  $userValidator
     * @return void
     */
    public function __construct(UserValidator $userValidator)
    {
        $this->middleware('guest');
        $this->validator = $userValidator;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Create a new user & account.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function createAccountAndUser(Request $request)
    {
        $inputs = $request->all();
        $errors = $this->validator->userValidator($inputs, new ValidateCreateUser());

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $user = User::create([
            'email' => $inputs['email'],
            'password' => Hash::make($inputs['password']),
        ]);

        $user->account()->create(['name' => $inputs['name']]);
        $user->userInfo()->create([]);
        $token = JWTAuth::fromUser($user);

        // TODO make transformer.
        return response([
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->userInfo->first_name,
                'last_name' => $user->userInfo->last_name,
                'country' => $user->userInfo->country,
                'city' => $user->userInfo->city,
                'address' => $user->userInfo->address,
                'phone_number' => $user->userInfo->phone_number,
                'mobile_phone' => $user->userInfo->mobile_phone,
                'account_id' => $user->account->id,
                'company_settings_done' => $user->account->company_settings_done,
                'user_settings_done' => $user->account->user_settings_done,
                'token' => $token
            ]
        ], Response::HTTP_OK);
    }
}
