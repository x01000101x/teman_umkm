<?php

namespace App\Http\Controllers;

use App\Events\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function message(){
        event(new Message(request('username'), request('message')));

        return [];
    }
}
