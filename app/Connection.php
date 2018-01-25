<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    protected $fillable = ['os', 'os_version', 'architecture', 'hostname', 'ip'];
}
