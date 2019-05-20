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
     * @param UserValidator  userValidator
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
    protected function createUser(Request $request)
    {
        $inputs = $request->all();
        $errors = $this->validator->userCreateValidator($inputs, new ValidateCreateUser());

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        $user = User::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => Hash::make($inputs['password']),
        ]);

        $user->account()->create();

        // TODO make transformer.
        return response([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'account_id' => $user->account->id
        ], Response::HTTP_OK);
    }
}
