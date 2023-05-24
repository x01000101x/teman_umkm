<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    public function index(){
        $user = new User;

        $showall = $user->all();

        if (!$showall){
            return response()->json([
                'message' => 'user not found'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);
    }

    public function userById($id){
        $user = new User;

        $show = $user->where('id', $id)->first();

        if (!$show){
            return response()->json([
                'message' => 'user not found'
            ],404);
        }

        return response()->json([
            'message' => $show
        ],200);
    }

    public function isiSaldo($id){
        $user = new User;

        $validator = Validator::make(request()->all(), [
            'saldo' => 'required'
        ]);


         if ($validator->fails()) {
             return response()->json([
                'message' => 'please complete the form'
            ], 403);
           }

        $getUser = $user->where('id', $id)->first();

        if (!$getUser){
            return response()->json([
                'message' => 'user not found'
            ],404);
        }

        $saldoUser = floatval($getUser->saldo);
        $saldoKredit = floatval(request("saldo"));
        $summary = $saldoUser + $saldoKredit;
        $string = sprintf("%.2f", $summary);

        $getUser->saldo = $string;
        $getUser->update();

        return response()->json([
            'message' => $getUser->nama . " berhasil di kredit sebesar Rp. " . request("saldo")
        ],200);
    }
}
