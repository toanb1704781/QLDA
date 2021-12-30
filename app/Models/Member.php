<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public $timestamps = false;

    protected $table = "members";
    
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'proID', 'proID');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userID', 'userID');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'roleID', 'roleID');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment', 'memID', 'memID');
    }

    public function viewer()
    {
        return $this->hasMany('App\Models\Viewer', 'memID', 'memID');
    }
    
    public function issue()
    {
        return $this->hasMany('App\Models\Issue', 'memID', 'memID');
    }
    

    public static function isPM($proID){
        $member = Member::where('proID', $proID)
                        ->where('roleID', '<>', 0)
                        ->where('roleID', '<>', 5)
                        ->get();
        foreach ($member as $mem) {
            if ($member->roleID == 1 && $member->userID == session()->get('userID')) {
                return true;
            }else{
                return false;
            }
        }

    }
}
