<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project_status extends Model
{
    public $timestamps = false;
    
    public function project()
    {
        return $this->hasMany('App\Models\Project', 'statusID', 'statusID');
    }
    
}
