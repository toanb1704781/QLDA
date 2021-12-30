<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->hasMany('App\Models\User', 'infoID', 'infoID');
    }
    
}
