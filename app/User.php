<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Connection;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function BeerProfiles(){
        return $this->hasMany('App\BeerProfile', 'account_id');
    }

    public function SensorData(){
        return $this->hasMany('App\SensorData', 'account_id');
    }

    public function Connections(){
        return $this->hasOne('App\Connection', 'id');
    }

    public function updateConnInfo($connInfo){
        $connInfo['ip'] = $_SERVER['REMOTE_ADDR'];
        if($this->Connections()->find($this->id)){
            $this->Connections()->update($connInfo);
        }else{
            $conn = new Connection();
            $conn->fill($connInfo);
            $this->Connections()->save($conn);
        }

    }
}
