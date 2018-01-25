<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'sensor_data';
    protected $fillable = ['fridgeTemp', 'barrelTemp', 'cooler', 'heater', 'recordStamp'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\User', 'account_id');
    }
}
