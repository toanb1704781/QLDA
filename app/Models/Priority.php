<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    public $timestamps = false;
    protected $table = "priorities";

    public function issue()
    {
        return $this->hasMany('App\Models\Issue', 'priorityID', 'priorityID');
    }
    
}
