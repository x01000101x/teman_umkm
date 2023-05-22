<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register()
    {
        request()->validate([
            'nama' => 'required|string',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|min:8',
            'no_hp' => 'required|numeric',
            'tipe_akun' => 'required',
            'password' => 'required|min:8'
        ]);

        $user = new User;
        $user->nama = request('nama');
        $user->email = request('email');
        $user->no_hp = request('no_hp');
        $user->tipe_akun = request('tipe_akun');
        $user->password = Hash::make(request('password'));
        $user->save();

        return response()->json([
            'message' => 'akun berhasil dibuat silahkan login',
            'data' => $user
        ], 200);
    }

    public function login()
    {
        $user = User::where('email', request('email'))->first();

        if (!$user || !Hash::check(request('password'), $user->password)) {
            return response()->json([
                'error' => 'Email atau password salah'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'berhasil login',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout()
    {
        $user = request()->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'berhasil logout'
        ]);
    }

    public function logoutById($id)
    {
        PersonalAccessToken::where("tokenable_id", $id)->delete();

        return response()->json([
            'message' => 'berhasil logout'
        ]);
    }

    public function validateResetPassword(){
        $user = new User;
        $passwordReset = new PasswordReset;
        $checkEmail = $user->where('email', request('email'))->first();


        if (count(array($checkEmail)) < 1) {
            return response()->json([
                'message' => 'user not found!'
            ]);
        }

        $passwordReset->email = request('email');
        $passwordReset->token = Str::random(40);
        $passwordReset->created_at = Carbon::now();
        $passwordReset->save();

        if ($this->sendResetEmail($passwordReset->email, $passwordReset->token)){
            return response()->json([
                'message' => 'success! a reset link has been sent to your email!'
            ]);
        }else{
            return response()->json([
                'message' => 'network error, please try again'
            ], 400);
        }
    }

    public function sendResetEmail($email, $token){
        $user = new User;
        $user = $user->where('email', $email)->select('nama', 'email')->first();

        $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);
        try {
            //Here send the link with CURL with an external email API
                return true;
            } catch (\Exception $e) {
                return false;
            }
    }

    public function resetPassword(){
        $passwordReset = new PasswordReset;
        $user = new User;
        $validator = Validator::make(request()->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed',
            'token' => 'required' ]);


         if ($validator->fails()) {
             return response()->json([
                'message' => 'please complete the form'
            ], 403);
           }


        $password = request('password');

        $tokenData = $passwordReset->where('token', request('token'))->first();

        if (!$tokenData){
            return response()->json([
                'message' => 'invalid token'
            ], 400);
        }

        $userEmail = $user->where('email', $tokenData->email)->first();

        if (!$userEmail){
            return response()->json([
                'message' => 'user not found!'
            ], 404);
        };

        $userEmail->password = Hash::make($password);
        $userEmail->update();

        Auth::login($userEmail);

        $passwordReset->where('email', $user->email)->delete();

        if ($this->sendSuccessEmail($tokenData->email)) {
            return response()->json([
                'message' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'network error, please try again'
            ], 400);
        }


        }

}
