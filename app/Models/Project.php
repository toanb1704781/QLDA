<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Project extends Model
{
    public $timestamps = false;

    protected $table = "projects";
    
    public function member()
    {
        return $this->hasMany('App\Models\Member', 'proID', 'proID');
    }

    public function issue()
    {
        return $this->hasMany('App\Models\Issue', 'proID', 'proID');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'userID');
    }

    public function project_status()
    {
        return $this->belongsTo('App\Models\Project_status', 'statusID', 'statusID');
    }
    
    public function date()
    {
        return $this->belongsTo('App\Models\Date', 'dateID', 'dateID');
    }

    public function access()
    {
        return $this->hasMany('App\Models\Access', 'proID', 'proID');
    }
    

    public static function allProject(){
        $project = Project::where('statusID', 1)->get();
        return $project;
    }

    public static function getAllMyProject(){
        if (session()->get('userID') == 1) {
            $project = Project::where('statusID', 1)->get();
            return $project;
        }else{
            $member = Member::where('userID', session()->get('userID'))
                            ->where('roleID', 1)
                            ->get();
            if ($member->isNotEmpty()) {
                foreach ($member as $mem) {
                    $data_proID[] = $mem->proID;
                }
                $project = Project::whereIn('proID', $data_proID)->get();
            }
            return $project ?? null;
        }
    }

    public function isPM($proID){
        $member = Member::where('proID', $proID)
                        ->where('userID', session()->get('userID'))
                        ->where('roleID', 1)
                        ->first();
        if(isset($member)){
            return true;
        }else{
            return false;
        }

    }

    public function isViewer($proID){
        $member = Member::where('proID', $proID)
                        ->where('userID', session()->get('userID'))
                        ->where('roleID', 5)
                        ->first();
        if(isset($member)){
            return true;
        }else{
            return false;
        }
    }

    public function getDefaultAssignee($memID){
        $member = Member::where('memID', $memID)->first();
        return $member->user->username;
    }
    
    
    // đếm công việc của tôi
    public function countMyIssue($proID, $status){
        if (session()->get('userID') == 1) {
            if ($status == 0) {
                $issue = Issue::where('proID', $proID)->get();
                return count($issue);
            }
            if ($status == 5) {
                $issue = Issue::where('proID', $proID)
                                ->where('workflowID', 5)
                                ->get();
                return count($issue);
            }
        }else{
            $member = Member::where('proID', $proID)
                            ->where('userID', session()->get('userID'))
                            ->whereNotIn('roleID', [4, 5])
                            ->first();
            if ($member) {
                if ($status == 0) {
                    $issue = Issue::where('memID', $member->memID)->get();
                    return count($issue);
                }
                if ($status == 5) {
                    $issue = Issue::where('memID', $member->memID)
                                    ->where('workflowID', 5)
                                    ->get();
                    return count($issue);
                }
            }
        }
    }
}
