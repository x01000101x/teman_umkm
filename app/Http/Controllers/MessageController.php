<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response s
     */
    public function index()
    {
        $id = Auth::id();
        $datas = Message::where('receiver_id', $id)->get()->toArray();
        foreach ($datas as $data => $value) {
            $datar = User::where('id', $value['sender_id'])->first();
            array_push($datas[$data],$datar['nama']);
        }
        return response()->json([
            'message' => $datas,
            // 'sender' => $sender->nama

        ]);

    }

     public function getById($id)
    {
        $datas = Message::where('id', $id)->first()->toArray();
        $datas["sender"] = User::where('id', $datas['sender_id'])->select('nama')->first();
        return response()->json([
            'message' => $datas,

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
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        $message = new Message;

        $message->sender_id = Auth::id();
        $message->receiver_id = $id;
        $message->subject = request('subject');
        $message->description = request('description');
        $message->time = Carbon::now()->format('H:i:s');
        $message->date = Carbon::now()->format('d-m-Y');
        $message->save();

        return response()->json([
            'message' => 'chat sent!',
            'data' => $message
        ]);

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
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
