<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{

    public function connStats(){
        return view('stats.connection');
    }

    public function tempStats(){
        return view('stats.temperature');
    }
}
