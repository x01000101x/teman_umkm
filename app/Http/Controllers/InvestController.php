<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Invest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;



class InvestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invest = Invest::get();

        return response()->json([
            'message' => $invest
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        request()->validate([
            'nominal' => 'required|string',
            'no_rek' => 'required|string',
            'opsi_pembayaran' => 'required|string',
            'nama_rek' => 'required|string',
        ]);

        $startDate = Carbon::now()->format('d-m-Y');
        $futureDate = Carbon::now()->addYear()->format('d-m-Y');

       $fund = new Fund;

      $getFund = $fund->where('id', $id)->first();

        if (!$getFund) {
            return response()->json([
                'message' => 'maaf UMKM tidak ditemukan'
            ], 403);
        }

        $nominal = request('nominal');
        $percent = floatval($getFund->profit) / 100;


        $total = (floatval($getFund->total_funds) + floatval($nominal)) * $percent;
        $new_total = number_format((float)$total, 2, '.', '');


        $user_id = Auth::id();

        $invest = new Invest;
        $invest->user_id = $user_id;
        $invest->fund_id = $id;
        $invest->nominal = request('nominal');
        $invest->status = "0";
        $invest->no_rek = request("no_rek");
        $invest->opsi_pembayaran = request("opsi_pembayaran");
        $invest->nama_rek = request("nama_rek");
        $invest->start_date = $startDate;
        $invest->end_date = $futureDate;
        $invest->dividen = sprintf("%.2f", $new_total);;
        $invest->nama_rek = request("nama_rek");
        $invest->image = request("image");
        $invest->save();

        return response()->json([
            'message' => 'invest berhasil dibuat! silahkan menunggu approval',
            'data' => $invest
        ], 200);
    }

   public function calculator($id){
        $fund = new Fund;

        $getFund = $fund->where('id', $id)->first();

        if (!$getFund) {
            return response()->json([
                'message' => 'maaf UMKM tidak ditemukan'
            ], 403);
        }

        request()->validate([
            'nominal' => 'required|string',
        ]);

        $nominal = request('nominal');
        $percent = floatval($getFund->profit) / 100;

        $totalFund = floatval($getFund->target_funds) - (floatval($getFund->total_funds) + floatval($nominal));
        $total = (floatval($getFund->total_funds) + floatval($nominal)) * $percent;
        $new_total = number_format((float)$total, 2, '.', '');
        if(floatval($nominal) <= $totalFund){
            return response()->json([
                'message' => $new_total,
            ], 200);
        } else{
            return response()->json([
                'message' => "Melebihi Target Modal UMKM",
            ], 200);
        }
   }
}
