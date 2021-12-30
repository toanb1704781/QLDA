<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access;

class Access extends Model
{
    public $timestamps = false;
    protected $table = 'access';

    public static function recent_project(){
        $access = Access::where('userID', session()->get('userID'))
                        ->where('issueID', null)
                        ->where('proID', '<>', null)
                        ->orderBy('created_at', 'desc')
                        ->paginate(5);
        return $access;
    }

    public static function recent_issue(){
        $access = Access::where('userID', session()->get('userID'))
                        ->where('proID', null)
                        ->where('issueID', '<>', null)
                        ->orderBy('created_at', 'desc')
                        ->paginate(5);
        return $access;
    }
    
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'proID', 'proID');
    }

    public function issue()
    {
        return $this->belongsTo('App\Models\Issue', 'issueID', 'issueID');
    }
    
}
