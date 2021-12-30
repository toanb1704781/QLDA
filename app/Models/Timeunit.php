<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Timeunit extends Model
{
    public $timestamps = false;
    protected $table = 'timeunits';

    public static function getAllTimeunit($string){
        $timeunit = Timeunit::where('unit', $string)->first();
        return $timeunit->timeUnit;
    }

    public static function getTimeUnit($string){
        $unit = Str::substr($string, -1);
        $timeunit = Timeunit::where('unit', $unit)->first();
        if ($unit == "d") {
            $int_time = Str::ascii(Str::of($string)->before('d'));
            return  $int_time * $timeunit->timeUnit;
        }else{
            if ($unit == "h") {
                $int_time = Str::ascii(Str::of($string)->before('h'));
                return  $int_time * $timeunit->timeUnit;
            }
        }
    }
}
