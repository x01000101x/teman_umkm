<?php

namespace App\Http\Controllers;

use App\Models\Dividen;
use App\Models\Invest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class DividenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $get_user_id = Auth::id();

       $invest = new Invest;

       $get_dividen = $invest->where('user_id', $get_user_id)->get();

       if(!$get_dividen){
        return response()->json([
            'message' => "maaf tidak ada investasi"
        ], 404);
       }
         return response()->json([
             'message' => $get_dividen
         ], 200);

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
            'opsi_pembayaran' => 'required|string',
            'nama_rek' => 'required|string',
            'no_rek' =>'required|string'
        ]);

       $get_user_id = Auth::id();

       $invest = new Invest;
       $dividen = new Dividen;

    $get_dividen = $invest->where('id', $id)->where('user_id', $get_user_id)->first();

    if(!$get_dividen){
        return response()->json([
            'message' => "maaf tidak ada investasi"
        ], 404);
       }

      $cair = request("nominal");

      $rumus = floatval($get_dividen->dividen) - floatval($cair);
      $new_total = number_format((float)$rumus, 2, '.', '');

      if($rumus >= 0){
        $dividen->id_investasi = $get_dividen->id;
        $dividen->total_dividen = $get_dividen->dividen;
        $dividen->nominal_pencairan = $new_total;
        $dividen->opsi_pembayaran =request("opsi_pembayaran");
        $dividen->nama_rek = request("nama_rek");
        $dividen->status = "0";
        $dividen->save();

          return response()->json([
              'message' => $dividen
          ], 200);
      }else{

        return response()->json([
            'message' => "maaf nominal terlalu besar"
        ], 403);
      }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dividen  $dividen
     * @return \Illuminate\Http\Response
     */
    public function show(Dividen $dividen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dividen  $dividen
     * @return \Illuminate\Http\Response
     */
    public function edit(Dividen $dividen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dividen  $dividen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dividen $dividen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dividen  $dividen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dividen $dividen)
    {
        //
    }
}
