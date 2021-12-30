<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Date extends Model
{
    public $timestamps = false;
    // protected $dates = ['startDate'];
    
    public function project()
    {
        return $this->hasMany('App\Models\Project', 'dateID', 'dateID');
    }

    public static function getDateNow(){
        return Carbon::now('Asia/Ho_Chi_Minh')->isoFormat('YYYY-MM-DD');
    }

    public function formatDate($date){
        return $formatDate = Carbon::create($date)->isoFormat('DD-MM-YYYY');;
    }
    
    public function countDay($dateID){
        $date = Date::where('dateID', $dateID)->first();
        $startDate = Carbon::create($date->startDate);
        $endDate = Carbon::create($date->endDate);
        return $endDate->diffInDays($startDate);    
    }
}
