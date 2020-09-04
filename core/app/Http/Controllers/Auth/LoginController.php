<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validate;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login',[
            'title' => 'User Login',
            'loginRoute' => 'login',
            'forgotPasswordRoute' => 'password.request',
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function login(Request $request)
     {
         $input = $request->all();

         $messages = [
            "username.exists" => 'Username / Email Salah.',
            "password.required" => 'Password Wajib Diisi!',
            "username.required" => 'Username / Email Wajib Diisi!'
        ];

         $this->validate($request, [
             'username' => 'required',
             'password' => 'required',
         ], $messages);

         $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
         if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
         {
             return redirect()->route('beranda');
         }else{
            return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                'username' => 'Username atau Password Salah!',
            ]);
         }

     }



}
