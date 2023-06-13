<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Order;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
       return Post::where('id', $id)->first();
    }

    public function order(Request $request)
    {
        $data = $request->input();
        $user_id = Auth::id();
        $date = strval(Carbon::now()->getPreciseTimestamp(3));
        $dmy= strval(Carbon::now()->format('d-m-Y'));

        foreach($data["data"] as $key => $value){
            $order = new Order();
            // dd($value["id"]);
            $id_pesanan = "UMKM-" . $dmy . "-" . $date;
            $post = Post::where('id', $value["id"])->first();
            $total = floatval($request->jumlah) * floatval($post->harga);
            $order->jumlah = $value["jumlah"];
            $order->email = $value["email"];
            $order->no_hp = $value["no_hp"];
            $order->user_id = $user_id;
            $order->id_pesanan = $value["id"];
            $order->order_id = $id_pesanan;
            $order->judul = $post->judul;
            $order->total = $total;
            $order->status = "0";
            $order->save();

        }

        return response()->json([
            'message' => "success",
            'data' => $data
        ], 200);

        // dd($post);

        // $users = new User;
        // $akun = $users->where('id', $user_id)->first();

        // $rumus = floatval($akun->saldo) - floatval($total);

        // $answers = [];
        // for ($i = 0; $i < count($request->data); $i++) {
        //     $answers[] = [

        // ];
        // if($rumus >= 0){
            // $order->order_id = $id_pesanan;
            // $order->judul = $post->judul;
            // $order->jumlah = request('jumlah');
            // $order->total = $total;
            // $order->status = "0";
            // $order->no_hp = request('no_hp');
            // $order->email = request('email');

            // $order->save();
            // Order::insert($answers);




        }

        // }else{
        //     return response()->json([
        //         'message' => "maaf saldo tidak cukup mohon topup",
        //     ], 403);
        // }



    public function getCart(){
        request()->validate([
            'id' => 'required|array',
        ]);

        $post = new Post;

        $datas = request("id");

        $search = $post->whereIn('id', $datas)->get();

        if(!$search){
            return response()->json([
                'data' => 'pesanan tidak ada'
            ], 403);
        }

        return response()->json([
            'data' => $search
        ], 200);
    }

    public function getByEmail(){
        $order = new Order;

        $data = request("email");

        $search = $order->join('posts', 'orders.id_pesanan', '=', 'posts.id')->where('email', $data)->get();

        if(!$search){
            return response()->json([
                'data' => 'pesanan tidak ada'
            ], 403);
        }

        return response()->json([
            'data' => $search
        ], 200);
    }

}
