<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $funds = Fund::get();

        return response()->json([
            'message' => $funds
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        request()->validate([
            // 'user_id' => 'required',
            'title' => 'required|string',
            'details' => 'required',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
            'total_funds' => 'required|string',
            'target_funds' => 'required|string',
            // 'image' => 'string'
        ]);

        $id = Auth::id();

        $fund = new Fund;
        $fund->user_id = $id;
        $fund->title = request('title');
        $fund->details = request('details');
        $fund->start_date = request('start_date');
        $fund->end_date = request('end_date');
        $fund->total_funds = request('total_funds');
        $fund->target_funds = request('target_funds');
        $fund->image = request('image');
        $fund->save();

        return response()->json([
            'message' => 'crowd funding berhasil dibuat!',
            'data' => $fund
        ], 200);

    }

    public function cair($id){
        request()->validate([
            'nominal' => 'required|string',
        ]);

        $fund = new Fund;

        $funds = $fund->where('id', $id)->first();
        if (intval($funds->total_funds) - intval(request('nominal'))  >= 0){
            $funds->update([
            'is_cair'     => request('nominal')
        ]);
        return response()->json([
            'message' => 'berhasil cair!',
            'data' => $funds
        ], 200);
    } else{
        return response()->json([
            'message' => 'nominal terlalu besar!',
        ], 403);
    }
    }

    public function invest(){

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
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function show(Fund $fund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function edit(Fund $fund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fund $fund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fund  $fund
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fund $fund)
    {
        //
    }
}
