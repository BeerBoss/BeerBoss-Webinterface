<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\SensorData;
use App\Connection;
use Illuminate\Support\Facades\Auth;

class SensorDataController extends Controller
{
    public function index(){
        return Auth::user()->SensorData()->get();
    }

    public function store(Request $request){
        Auth::user()->updateConnInfo($request->connData);
        $data = new SensorData();
        $data->fill($request->tempData);
        $data->recordStamp = Carbon::now();
        Auth::user()->SensorData()->save($data);
        return response($data,201);

    }

    public function destroy($id){
        Auth::user()->SensorData()->where('id', $id)->delete();
        return 'Record with id ' . $id . ' has been deleted';
    }

    public function show($id){
        return Auth::user()->SensorData()->where('id', $id)->get();
    }

    public function getTemps(){
        return Auth::user()->SensorData()->get();
    }

    public function getLastTemp(){
        return Auth::user()->SensorData()->orderBy('id', 'desc')->first();
    }

    public function getDailyTemps(){
        return Auth::user()->SensorData()->where('recordStamp','>',Carbon::now()->format('yyyy-mm-dd'))->get();
    }
}
