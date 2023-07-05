<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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

    public function getFundById($id)
    {
       $funds = Fund::where('id', $id)->first();

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
            'email' => 'required|string',
            'no_telp' => 'required|string',
            'kategori' => 'required|string',
            'alamat' => 'required|string',
            'identitas_bisnis' => 'required',
            'proposal' => 'required',
            'image' => 'required'
        ]);

        $id = Auth::id();

        $image_64 = request('image'); //your base64 encoded data
        $image_binis = request('identitas_bisnis'); //your base64 encoded data
        $image_proposal = request('proposal'); //your base64 encoded data


        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_64, 0, strpos($image_64, ',')+1);

        //Identitas Bisnis
        $image_binis = request('identitas_bisnis'); //your base64 encoded data

        $extension2 = explode('/', explode(':', substr($image_binis, 0, strpos($image_binis, ';')))[1])[1];   // .jpg .png .pdf

        $replace = substr($image_binis, 0, strpos($image_binis, ',')+1);

         //Proposal
         $image_proposal = request('proposal'); //your base64 encoded data

         $extension3 = explode('/', explode(':', substr($image_proposal, 0, strpos($image_proposal, ';')))[1])[1];   // .jpg .png .pdf

         $replace = substr($image_proposal, 0, strpos($image_proposal, ',')+1);

      // find substring fro replace here eg: data:image/png;base64,

       $image = str_replace($replace, '', $image_64);
       $image2 = str_replace($replace, '', $image_binis);
       $image3 = str_replace($replace, '', $image_proposal);


       $image = str_replace(' ', '+', $image);
       $image2 = str_replace(' ', '+', $image2);
       $image3 = str_replace(' ', '+', $image3);


       $imageName = Str::random(10).'.'.$extension;
       $imageName2 = Str::random(10).'.'.$extension2;
       $imageName3 = Str::random(10).'.'.$extension3;


        // $imageName = Str::random(32).".".request('image')->getClientOriginalExtension();
        $fund = new Fund;
        $fund->user_id = $id;
        $fund->title = request('title');
        $fund->details = request('details');
        $fund->start_date = request('start_date');
        $fund->end_date = request('end_date');
        $fund->total_funds = request('total_funds');
        $fund->target_funds = request('target_funds');
        $fund->email = request('email');
        $fund->no_telp = request('no_telp');
        $fund->kategori = request('kategori');
        $fund->alamat = request('alamat');
        $fund->proposal = $imageName3;
        $fund->identitas_bisnis = $imageName2;
        $fund->image = $imageName;
        $fund->profit = request('profit');
        $fund->save();

        Storage::disk('public')->put($imageName2, base64_decode($image2));
        Storage::disk('public')->put($imageName3, base64_decode($image3));
        Storage::disk('public')->put($imageName, base64_decode($image));



        return response()->json([
            'message' => 'crowd funding berhasil dibuat!',
            'data' => $fund,
            'image' => '"http://localhost:8000/storage/" ' . $imageName
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

    public function getByEmail(){

        $auth = Auth::id();

        $user = new User;

        $search = $user->where('id', $auth)->first();

        if(!$search){
            return response()->json([
                'data' => 'pesanan tidak ada'
            ], 403);
        }

        return response()->json([
            'data' => $search
        ], 200);
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
