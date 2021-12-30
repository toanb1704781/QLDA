<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Logwork extends Model
{
    public $timestamps = false;
    protected $table = 'logworks';
    
    public function issue()
    {
        return $this->belongsTo('App\Models\Issue', 'issueID', 'issueID');
    }

    public function location()
    {
        return $this->belongsTo('App\Models\Location', 'locationID', 'locationID');
    }
    
    
    public function getDay($date){
        return Carbon::create($date)->day;
    }
}
