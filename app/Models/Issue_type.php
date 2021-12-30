<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue_type extends Model
{
    public $timestamps = false;

    
    public function issue()
    {
        return $this->hasMany('App\Models\Issue', 'typeID', 'typeID');
    }
    

    public static function allIssueType(){
        $issue_type = Issue_type::all();
        return $issue_type;
    }
}
