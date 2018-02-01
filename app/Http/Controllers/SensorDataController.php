<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\SensorData;
use Illuminate\Support\Facades\Auth;

class SensorDataController extends Controller
{
    public function index(){
        return Auth::user()->SensorData()->get();
    }

    //This function will be two functions in one. It will post the sensordata into the database, but it will also return the active profile
    public function store(Request $request){
        Auth::user()->updateConnInfo($request->connData);
        $data = new SensorData();
        $data->fill($request->tempData);
        $data->recordStamp = Carbon::now();
        Auth::user()->SensorData()->save($data);

        //Now it will get the active profile part and send this back
        $profile = Auth::user()->BeerProfiles()->whereNotNull('dateStarted')->first();
        if($profile) return $profile->getActivePart();
        else return null;

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
        $allTemps = Auth::user()->SensorData()->whereDate('recordStamp','>=', date('Y-m-d'))->get();
        $collLength = $allTemps->count();
        $dataArray = collect();
        $lastInsert = 0;
        for($i = 0; $i < $collLength; $i++){
            if($i > $lastInsert + 12){
                $dataArray->push($allTemps[$i]);
                $lastInsert = $i;
            }
        }
        return $dataArray;
    }
}
