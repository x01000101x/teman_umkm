<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Order;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    public function order($id)
    {
        $date = strval(Carbon::now()->getPreciseTimestamp(3));
        $dmy= strval(Carbon::now()->format('d-m-Y'));
        $id_pesanan = "UMKM-" . $dmy . "-" . $date;
        $post = Post::where('id', $id)->first();

        $total = request('jumlah') * $post->harga;


        $order = new Order();
        $order->id_pesanan = $id;
        $order->order_id = $id_pesanan;
        $order->judul = $post->judul;
        $order->jumlah = request('jumlah');
        $order->total = $total;
        $order->status = "0";
        $order->no_hp = request('no_hp');
        $order->email = request('email');

        $order->save();

        return response()->json([
            'message' => "success",
            'data' => $order
        ], 200);
    }

}
