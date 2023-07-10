<?php

namespace App\Http\Controllers;

use App\Models\Dividen;
use App\Models\Fund;
use App\Models\Invest;
use App\Models\Order;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



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

    public function dividen(){
        $dividen = new Dividen;

        $showall = $dividen->all();

        if (!$showall){
            return response()->json([
                'message' => 'dividen not found'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);
    }

    public function invest(){
        $invest = new Invest;

        $showall = $invest->all();

        if (!$showall){
            return response()->json([
                'message' => 'invest not found'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);
    }

    public function order(){
        $order = new Order;

        $showall = $order->all();

        if (!$showall){
            return response()->json([
                'message' => 'order not found'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);
    }

     public function fund(){
        $fund = new Fund;

        $showall = $fund->all();

        if (!$showall){
            return response()->json([
                'message' => 'fund not found'
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

    public function statusUser($id){
        $user = new User;

        $validator = Validator::make(request()->all(), [
            'status' => 'required'
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

        $getUser->status = request('status');
        $getUser->update();

        return response()->json([
            'message' => $getUser->nama . " berhasil di ubah status = " . request("status")
        ],200);
    }

    public function dividenStatus($id){
        $dividen = new Dividen;

        $validator = Validator::make(request()->all(), [
            'status' => 'required'
        ]);


         if ($validator->fails()) {
             return response()->json([
                'message' => 'please complete the form'
            ], 403);
           }

        $getdividen = $dividen->where('id', $id)->first();

        if (!$getdividen){
            return response()->json([
                'message' => 'dividen not found'
            ],404);
        }

        $getdividen->status = request('status');
        $getdividen->update();

        return response()->json([
            'message' => "berhasil di ubah status = " . request("status")
        ],200);
    }

    public function orderStatus($id){
        $order = new Order;

        $validator = Validator::make(request()->all(), [
            'status' => 'required'
        ]);


         if ($validator->fails()) {
             return response()->json([
                'message' => 'please complete the form'
            ], 403);
           }

        $getorder = $order->where('id', $id)->first();

        if (!$getorder){
            return response()->json([
                'message' => 'order not found'
            ],404);
        }

        $getorder->status = request('status');
        $getorder->update();

        return response()->json([
            'message' => "berhasil di ubah status = " . request("status")
        ],200);
    }

    public function investStatus($id){
        $invest = new Invest;
        $fund = new Fund;

        $validator = Validator::make(request()->all(), [
            'status' => 'required'
        ]);


         if ($validator->fails()) {
             return response()->json([
                'message' => 'please complete the form'
            ], 403);
           }

        $getinvest = $invest->where('id', $id)->first();
        $getFund = $fund->where('id', $getinvest->fund_id)->first();

        if (!$getinvest){
            return response()->json([
                'message' => 'invest not found'
            ],404);
        }

        if (request('status') == "1"){
            $xyz = (floatval($getFund->total_funds) + floatval($getinvest->nominal));
            $newxyz = number_format((float)$xyz, 2, '.', '');
            $getFund->update([
                'total_funds'     => $newxyz,
            ]);
        }

        $getinvest->status = request('status');
        $getinvest->update();

        return response()->json([
            'message' => "berhasil di ubah status = " . request("status")
        ],200);
    }


    public function fundStatus($id){
        $fund = new Fund;

        $validator = Validator::make(request()->all(), [
            'status' => 'required'
        ]);


         if ($validator->fails()) {
             return response()->json([
                'message' => 'please complete the form'
            ], 403);
           }

        $getfund = $fund->where('id', $id)->first();

        if (!$getfund){
            return response()->json([
                'message' => 'fund not found'
            ],404);
        }

        $getfund->status = request('status');
        $getfund->update();

        return response()->json([
            'message' => "berhasil di ubah status = " . request("status")
        ],200);
    }

    public function addPost(){
        $post = new Post;

        request()->validate([
            'judul' => 'required|string',
            'sub_judul' => 'required|string',
            'harga' => 'required|string',
            'kategori' =>'required|string',
            'gambar' =>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $imageName = Str::random(32).".".request('gambar')->getClientOriginalExtension();
        $post->judul = request('judul');
        $post->sub_judul = request('sub_judul');
        $post->harga = request('harga');
        $post->kategori = request('kategori');
        $post->gambar = $imageName;
        $post->save();

        Storage::disk('public')->put($imageName, file_get_contents(request('gambar')));

        return response()->json([
            'message' => 'sukses menambah post',
            'data' => $post,
            'image' => '"http://localhost:8000/storage/" ' . $imageName
        ], 200);

    }

    public function GetListCair(){
        $fund = new Fund;

        $showall = $fund->where("is_cair", "0")->get();

        if (!$showall){
            return response()->json([
                'message' => 'belum ada yg cair'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);


    }

    public function GetListCairById($id){
        $fund = new Fund;

        $showall = $fund->where("id", $id)->first();

        if (!$showall){
            return response()->json([
                'message' => 'belum ada yg cair'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);


    }

    public function ChangeStatusCair($id){
        $fund = new Fund;

        $showall = $fund->where("id", $id)->first();

        $showall->update([
            'is_cair'     => request("status"),
        ]);

        if (!$showall){
            return response()->json([
                'message' => 'belum ada yg cair'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);


    }

    public function GetListCairInvest(){
        $fund = new Invest;

        $showall = $fund->where("is_cair", "0")->get();

        if (!$showall){
            return response()->json([
                'message' => 'belum ada yg cair'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);


    }

    public function GetListCairByIdInvest($id){
        $fund = new Invest;

        $showall = $fund->where("id", $id)->first();

        if (!$showall){
            return response()->json([
                'message' => 'belum ada yg cair'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);


    }

    public function ChangeStatusCairInvest($id){
        $fund = new Invest;
        $shock = new Fund;

        $showall = $fund->where("id", $id)->first();
        $getFund = $shock->where("id", $showall->fund_id)->first();

        $showall->update([
            'is_cair'     => request("status"),
        ]);

        // if (request("status") == "1"){
        //     $xyz = (floatval($getFund->total_funds) + floatval($showall->nominal));
        //     $newxyz = number_format((float)$xyz, 2, '.', '');
        //     $getFund->update([
        //         'total_funds'     => $newxyz,
        //     ]);

        // }


        if (!$showall){
            return response()->json([
                'message' => 'belum ada yg cair'
            ],404);
        }

        return response()->json([
            'message' => $showall
        ],200);


    }

}
