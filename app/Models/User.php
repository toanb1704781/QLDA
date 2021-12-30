<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Information;
use App\Models\User;

class User extends Authenticatable
{

    protected $table = 'accounts';
    public $timestamps = false;
    
    public function member()
    {
        return $this->hasMany('App\Models\Member', 'userID', 'userID');
    }

    public function project()
    {
        return $this->hasMany('App\Models\Project', 'created_by', 'userID');
    }

    public function information()
    {
        return $this->belongsTo('App\Models\Information', 'infoID', 'infoID');
    }

    public static function user_current(){
        $user = User::where('userID', session()->get('userID'))->first();
        return $user->information->name ?? $user->username ?? null;
    }

    public static function userInfo(){
        $user = User::where('userID', session()->get('userID'))->first();
        return $user;
    }

    public static function checkAdmin(){
        if (session()->get('userID') == 1) {
            return true;
        }else{
            return false;
        }
    }
    
    
}
