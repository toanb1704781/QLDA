<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Issue_type;
use App\Models\Project;
use App\Models\User;
use App\Models\Member;
use App\Models\Issue;
use Carbon\Carbon;

class Issue extends Model
{
    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo('App\Models\Member', 'memID', 'memID');
    }

    public function date()
    {
        return $this->belongsTo('App\Models\Date', 'dateID', 'dateID');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'proID', 'proID');
    }
    
    public function workflow()
    {
        return $this->belongsTo('App\Models\Workflow', 'workflowID', 'workflowID');
    }

    public function issue_type()
    {
        return $this->belongsTo('App\Models\Issue_type', 'typeID', 'typeID');
    }
    
    public function comment()
    {
        return $this->hasMany('App\Models\Comment', 'issueID', 'issueID');
    }

    public function access()
    {
        return $this->hasMany('App\Models\Access', 'issueID', 'issueID');
    }
    

    public function upload_file()
    {
        return $this->hasMany('App\Models\Upload_file', 'issueID', 'issueID');
    }
    
    public function priority()
    {
        return $this->belongsTo('App\Models\Priority', 'priorityID', 'priorityID');
    }
    
    // ============== các hàm xử lý ===============
        public function getReporter($reporter){
            $member = Member::where('memID', $reporter)->first();
            return $member->user->username;
        }

        public static function getAllIssueLogWork(){
            $member = Member::where('userID', session()->get('userID'))
                            ->whereNotIn('roleID', [4, 5])
                            ->get();
            if ($member->isNotEmpty()) {
                foreach ($member as $mem) {
                    $data_member[] = $mem->memID;
                }
                $issue = Issue::whereIn('memID', $data_member)
                                ->get();
            }
            return $issue ?? null;
        }

        public function equalDate($d1, $d2){
            $dt1 = Carbon::create($d1);
            $dt2 = Carbon::create($d2);
            if($dt1->equalTo($dt2)){
                return true;
            }else{
                return false;
            }
        }

        public function getOLA($issueID, $now){
            $issue= Issue::where('issueID', $issueID)->first();
            $created_at = Carbon::create($issue->created_at);
            $now->subMinutes(30);
            $ola =  $now->diffInMinutes($created_at, false);
            return $ola;
        }

        public function getSLA($issueID, $now){
            $issue= Issue::where('issueID', $issueID)->first();
            $ola = Carbon::create($issue->ola);
            $now->subHour(480);
            $sla =  $now->diffInHours($ola, false);
            return $sla;
        }
    
}
