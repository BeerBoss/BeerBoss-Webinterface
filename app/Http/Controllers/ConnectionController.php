<?php

namespace App\Http\Controllers;

use App\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function getLastConnection(){
        return Connection::where('id', Auth::user()->id)->first();
    }
}
