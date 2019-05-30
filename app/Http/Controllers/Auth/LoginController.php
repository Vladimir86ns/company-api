<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ValidateLoginUser;
use App\Validators\UserValidator;
use JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

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
        // $this->middleware('guest')->except('logout');
        $this->validator = $userValidator;
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $inputs = $request->only('email', 'password');
        $errors = $this->validator->userValidator($inputs, new ValidateLoginUser());

        if ($errors) {
            return response($errors, Response::HTTP_NOT_ACCEPTABLE);
        }

        if (Auth::attempt($inputs)) {
            $user = Auth::user();
            // TODO return from transformer
            return response([
                'id' => $user->id,
                'email' => $user->email,
                'account_id' => $user->account->id,
                'token' => JWTAuth::fromUser($user)
            ], Response::HTTP_OK);
        }

        return abort(Response::HTTP_NOT_FOUND, 'Credentials are not correct!');
    }
}
