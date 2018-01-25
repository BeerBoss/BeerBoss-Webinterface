<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeerProfileData extends Model
{
    protected $fillable = ['desiredTemp', 'amountDays'];

    public function BeerProfile(){
        return $this->belongsTo('App\BeerProfile');
    }
}
