<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\SensorData;

class SensorDataController extends Controller
{
    public function index(){
        return SensorData::all();
    }

    public function store(Request $request){
        $data = new SensorData();
        $data->fill($request->toArray());
        $data->recordStamp = Carbon::now();
        $data->save();
        return response($data,201);

    }

    public function destroy($data){
        $record = SensorData::where('id', $data)->first();
        if($record) $record->delete();
    }

    public function show($data){
        return SensorData::where('id', $data)->first();
    }

    public function __construct()
    {
        $this->middleware('auth.basic');
    }
}
