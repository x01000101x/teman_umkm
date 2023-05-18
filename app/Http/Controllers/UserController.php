<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

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
}
