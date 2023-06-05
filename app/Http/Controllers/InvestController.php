<?php

namespace App\Http\Controllers;

use App\Models\Invest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

        $user_id = Auth::id();

        $invest = new Invest;
        $invest->user_id = $user_id;
        $invest->fund_id = $id;
        $invest->nominal = request('nominal');
        $invest->status = "0";
        $invest->no_rek = request("no_rek");
        $invest->opsi_pembayaran = request("opsi_pembayaran");
        $invest->nama_rek = request("nama_rek");
        $invest->image = request("image");
        $invest->save();

        return response()->json([
            'message' => 'invest berhasil dibuat! silahkan menunggu approval',
            'data' => $invest
        ], 200);
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
     * @param  \App\Models\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function show(Invest $invest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function edit(Invest $invest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invest $invest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invest  $invest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invest $invest)
    {
        //
    }
}
