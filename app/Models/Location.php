<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;
    protected $table = 'locations';
    
    public function logwork()
    {
        return $this->hasMany('App\Models\Logwork', 'locationID', 'locationID');
    }
    
}
