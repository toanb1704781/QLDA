<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viewer extends Model
{
    public $timestamps = false;
    protected $table = 'viewers';

    public function member()
    {
        return $this->belongsTo('App\Models\Member', 'memID', 'memID');
    }

    public function issue()
    {
        return $this->belongsTo('App\Models\Issue', 'issueID', 'issueID');
    }
    
}
