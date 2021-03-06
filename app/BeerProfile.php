<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BeerProfile extends Model
{
    protected $fillable = ['name'];

    public function BeerProfileData(){
        return $this->hasMany('App\BeerProfileData');
    }

    public function toggle($active){
        if($active) $this->dateStarted = date('Y-m-d');
        else $this->dateStarted = null;
        $this->save();
    }

    public function getActivePart(){
        $searchDay = 0;
        $currentDay = Carbon::today()->diffInDays(Carbon::parse($this->dateStarted));
        foreach ($this->BeerProfileData as $data){
            $searchDay += $data->amountDays;
            if($currentDay < $searchDay){
                return $data;
            }
        }
    }
}
