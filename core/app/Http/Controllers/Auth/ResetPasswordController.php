<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    public function reset(Request $request)
    {
        // $credentials = request()->validate([
        //     'email' => 'required|email',
        //     'token' => 'required|string',
        // ]);
        $rules = [
            'password' => 'required|string|confirmed'
        ];

        $pesan = [
            'password.required' => 'Password Baru Wajib Diisi!',
            'password.confirmed' => 'Password baru yang dimasukan tidak sama!',
        ];
        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'msg' => 'Terdapat Kesalahan Di Form!',
                'errors' => $validator->errors(),
            ]);
        }else{
            $credentials = array(
                'token' => $request->token,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'password_confirmation' => $request->password_confirmation,
            );
            $reset_password_status = Password::reset($credentials, function ($user, $password) {
                $user->password = $password;
                $user->save();
            });
            if($reset_password_status == Password::INVALID_TOKEN)
            {
                return response()->json([
                    'fail' => true,
                    'msg' => 'Token Reset Password Sudah Digunakan!',
                ]);
            }else{
                return response()->json([
                    'fail' => false,
                ]);
            }
        }
    }
}
