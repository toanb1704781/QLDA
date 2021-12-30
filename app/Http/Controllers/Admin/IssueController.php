<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Date;
use App\Models\Project_status;
use App\Models\Member;
use App\Models\Role;
use App\Models\Workflow;
use App\Models\Issue;
use App\Models\Comment;
use App\Models\Upload_file;
use App\Models\Viewer;
use App\Models\Access;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\Priority;
use App\Models\Issue_type;
use App\Models\Logwork;

class IssueController extends Controller
{
    private $pathView = 'admin.contents.issue.issue_';

    public function index($proID){
        $project = Project::where('proID', $proID)->first();
        $member = Member::where('proID', $proID)
                        ->where('roleID', '<>', 0)
                        ->get();
        $issue = Issue::where('proID', $proID)->orderBy('created_at')->get();
        return view($this->pathView .'index', [
            'pro' => $project,
            'member' => $member,
            'issue' => $issue,
        ]);
    }

    // =================== Công việc của tôi ====================================
        public function myIssue(Request $request){
            $userID = session()->get('userID');
            $value = $request->filterIssue;
            if($value == 'my-issues'){
                $member = member::where('userID', $userID)
                                ->whereNotIn('roleID', [4, 5])
                                ->get();
                if(!$member->isEmpty()){
                    foreach ($member as $mem) {
                        foreach ($mem->issue as $issue) {
                            $data_issueID[] = $issue->issueID; 
                        }
                    }
                }
    
            }else{
                if ($value == 'issues-share') {
                    $member_viewer = member::where('userID', $userID)
                                ->where('roleID', 5)
                                ->first();
    
                    if (isset($member_viewer)) {
                        $viewer = $member_viewer->viewer;
                        if(!$viewer->isEmpty()){
                            foreach ($viewer as $viewer) {
                                $data_issueID[] = $viewer->issueID; 
                            }
                        }
                    }
                }else{
                    $member = member::where('userID', $userID)
                                    ->whereNotIn('roleID', [4, 5])
                                    ->get();
                    if(!$member->isEmpty()){
                        foreach ($member as $mem) {
                            foreach ($mem->issue as $issue) {
                                $data_issueID[] = $issue->issueID; 
                            }
                        }
                    }

                    $member_viewer = member::where('userID', $userID)
                                    ->where('roleID', 5)
                                    ->first();

                    if (isset($member_viewer)) {
                        $viewer = $member_viewer->viewer;
                        if(!$viewer->isEmpty()){
                            foreach ($viewer as $viewer) {
                                $data_issueID[] = $viewer->issueID; 
                            }
                        }
                    }
                }
            }
           
            if (isset($data_issueID)) {
                $issue = issue::whereIn('issueID', $data_issueID)
                                ->orderBy('created_at')
                                ->get();
            }

            return view($this->pathView .'myIssue',[
                'issue' => $issue ?? null,
                'userID' => $userID,
                'value' => $value,
            ]);
        }

        public function postShareIssue(Request $request, $issueID){
            $issue = issue::where('issueID', $issueID)->first();
            $userID_share = $request->userID_share;
            foreach ($userID_share as $userID) {
                $member = member::where('userID', $userID)
                                ->where('roleID', 5)
                                ->first();
                if (isset($member)) {
                    $memID = $member->memID;
                }else{
                    $member = new member;
                    $member->memID = random_int(1000, 9999);
                    $member->userID = $userID;
                    $member->proID = $issue->proID;
                    $member->roleID = 5;
                    $member->save();

                    $memID =  $member->memID;
                }

                $viewer = new viewer;
                $viewer->viewerID = random_int(1000, 9999);
                $viewer->memID = $memID;
                $viewer->issueID = $issueID;
                $viewer->save();

                // Thông báo
                    $notification = new notification;
                    $notification->notiID = random_int(10000, 99999);
                    $notification->userID = $userID;
                    $notification->notiUrl = route('issue.show', ['issueID' => $issueID]);
                    $notification->created_by = session()->get('userID');
                    $notification->notiTypeID = 3;
                    $notification->save();
            }

            return redirect()->back();
        }

    // ============== Thêm công việc ================
        public function store(Request $request, $proID){
            $project = Project::where('proID', $proID)->first();

            $date = new Date;
            $date->dateID = random_int(10000, 99999);
            $date->startDate = $request->startDate;
            $date->endDate = $request->endDate;
            $date->save();

            $issue = new Issue;
            $issue->issueID = random_int(10000, 99999);
            $issue->issueKey = $project->proKey .'-'. (count($project->issue) + 1);
            $issue->proID = $proID;
            $issue->workflowID = 1;
            $issue->dateID = $date->dateID;
            $issue->typeID = $request->typeID;
            $issue->summary = $request->summary;
            $issue->issueDesc = $request->issueDesc;
            $issue->memID = $request->memID;
            $issue->reporter = $request->reporter;
            $issue->priorityID = $request->priorityID;
            $issue->original_estimate = $request->original_estimate;
            $issue->save();

            // Thông báo
                $member = Member::where('memID', $request->memID)->first();

                $notification = new Notification;
                $notification->notiID = random_int(10000, 99999);
                $notification->userID = $member->userID;
                $notification->notiUrl = route('issue.show', ['issueID' => $issue->issueID]);
                $notification->created_by = session()->get('userID');
                $notification->notiTypeID = 2;
                $notification->save();

            return redirect()->back();
    
    }

    public function show($issueID){
        $issue = Issue::where('issueID', $issueID)->first();
        $project = Project::where('proID', $issue->proID)->first();
        $priority = Priority::all();
        $issue_type = Issue_type::all();
        $allMember = Member::where('proID', $project->proID)
                            ->where('roleID', '<>', 0)
                            ->get();
        $member = Member::where('proID', $issue->proID ?? null)
                        ->where('roleID', '<>', 0)
                        ->where('roleID', '<>', 5)
                        ->get();
        $workflow = Workflow::all();
        $comment = Comment::where('repCmt', null)
                            ->where('issueID', $issueID)
                            ->orderBy('created_at')
                            ->get();
        $upload_file = Upload_file::where('issueID', $issueID)
                                    ->get();

        foreach ($member as $mem) {
            $data_member[] = $mem->userID;
        }
        $user = User::where('userID', '<>', 1)
                    ->where('isActive', 1)
                    ->whereNotIn('userID', $data_member)
                    ->orderBy('username')
                    ->get();

         // Tạo truy cập issue gần nhất
        $access_old = Access::where('issueID', $issueID)
                            ->where('userID', session()->get('userID')) 
                            ->first();
        if (isset($access_old)) {
            Access::where('issueID', $issueID)
                    ->where('userID', session()->get('userID'))
                    ->update([
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                    ]);
        }else{
            $access = new Access;
            $access->accessID = random_int(10000, 99999);
            $access->userID = session()->get('userID');
            $access->issueID = $issueID;
            $access->save();
        }
        return view($this->pathView .'show',[
            'issue' => $issue,
            'workflow' => $workflow,
            'member' => $member,
            'allMember' => $allMember,
            'created_by' => $issue->project->created_by,
            'comment' => $comment,
            'upload_file' => $upload_file,
            'allUser' => $user,
            'project' => $project,
            'priority' => $priority,
            'issue_type' => $issue_type,
        ]);
    }

    public function update(Request $request, $issueID){
       Issue::where('issueID', $issueID)
            ->update([
                'typeID' => $request->typeID,
                'summary' => $request->summary,
                'issueDesc' => $request->issueDesc,
                'memID' => $request->memID,
                'reporter' => $request->reporter,
                'priorityID' => $request->priorityID,
                'original_estimate' => $request->original_estimate,
            ]);
        $issue = Issue::where('issueID', $issueID)->first();
        Date::where('dateID', $issue->dateID)
            ->update([
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
            ]);
        
         // Thông báo
            $member = Member::where('memID', $request->memID)->first();

            $notification = new Notification;
            $notification->notiID = random_int(10000, 99999);
            $notification->userID = $member->userID;
            $notification->notiUrl = route('issue.show', ['issueID' => $issue->issueID]);
            $notification->created_by = session()->get('userID');
            $notification->notiTypeID = 2;
            $notification->save();

        return redirect()->back();
    }

    // Lọc issue
    public function filterIssue(Request $request, $proID)
    {
        if ($request->filterIssue >= 6) {
            if ($request->filterIssue == 6) {
                return redirect()->route('quanlyduan.show', [
                    'quanlyduan' => $proID,
                ]);
            } else{
                if ($request->filterIssue == 7) {
                    $mem = Member::where('userID', session()->get('userID'))
                                    ->where('roleID', '<>', 0)
                                    ->where('proID', $proID)
                                    ->first();
                    $issue = Issue::where('proID', $proID)
                                    ->where('memID', $mem->memID)
                                    ->get();
                    $member = Member::where('proID', $proID)
                                    ->where('roleID', '<>', 0)
                                    ->get();
                    $workflow = Workflow::all();
                    $project = Project::where('proID', $proID)->first();
                    return view('admin.contents.project.project_show', [
                        'project' => $project,
                        'issue' => $issue,
                        'member' => $member,
                        'workflow' => $workflow,
                        'value' => $request->filterIssue,
                        'i' => 1,
                    ]);
                }
            }
            
        }else{
            $issue = Issue::where('proID', $proID)
                            ->where('workflowID', $request->filterIssue)
                            ->get();
            $project = Project::where('proID', $proID)->first();
            $member = Member::where('proID', $proID)
                            ->where('roleID', '<>', 0)
                            ->get();
            $workflow = Workflow::all();
            return view('admin.contents.project.project_show', [
                'project' => $project,
                'issue' => $issue,
                'member' => $member,
                'workflow' => $workflow,
                'value' => $request->filterIssue,
                'i' => 1,
            ]);
        }
    }
    public function workflow(Request $request, $issueID){
        $issue = Issue::where('issueID', $issueID)->first(); 
        Issue::where('issueID', $issueID)
            ->update([
                'workflowID' => $request->workflowID,
            ]);
        return redirect()->back();
    }

    // Bình luận
    public function comment(Request $request, $issueID){
        $issue = Issue::where('issueID', $issueID)->first();
        $member = Member::where('userID', session()->get('userID'))
                        ->where('proID', $issue->proID)
                        ->where('roleID', '<>', 0)
                        ->first();
        $comment = new Comment;
        $comment->cmtID = random_int(10000, 99999);
        $comment->comment = $request->comment;
        $comment->memID = $member->memID;
        $comment->issueID = $issueID;
        $comment->tag = $request->selectMember ?? null;
        $comment->save();

        // Thông báo
            if ($request->selectMember != null) {
                $notification = new Notification;
                $notification->notiID = random_int(10000, 99999);
                $notification->userID = $request->selectMember;
                $notification->notiUrl = route('issue.show', ['issueID' => $issueID]);
                $notification->created_by = session()->get('userID');
                $notification->notiTypeID = 4;
                $notification->save();
            }

        if ($request->hasFile('cmt_files')) {
            $files = $request->file('cmt_files');
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $filename = random_int(100000, 999999) .'_' .$name;
                $file->storeAs('public/uploads/', $filename );
                $fullname = 'storage/uploads/' .$filename;
                $file = new Upload_file;
                $file->fileName = $filename;
                $file->issueID = $issueID;
                $file->cmtID = $comment->cmtID;
                $file->save();
            }
        }

        return redirect()->back();
    }

    public function udpComment(Request $request, $cmtID){
        $comment = Comment::where('cmtID', $cmtID)->first();
        Comment::where('cmtID', $cmtID)
                ->update([
                    'comment' => $request->comment,
                ]);
        if ($request->hasFile('cmt_files')) {
            $files = $request->file('cmt_files');
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $filename = random_int(100000, 999999) .'_' .$name;
                $file->storeAs('public/uploads/', $filename );
                $fullname = 'storage/uploads/' .$filename;
                $file = new Upload_file;
                $file->fileName = $filename;
                $file->issueID = $comment->issueID;
                $file->cmtID = $cmtID;
                $file->save();
            }
        }
        return redirect()->back();
    }

    public function desComment($cmtID){
        $comment = Comment::where('cmtID', $cmtID);
        $comment->delete();
        return redirect()->back();
    }

    public function repComment(Request $request, $cmtID){
        $cmt = Comment::where('cmtID', $cmtID)->first();
        $member = Member::where('proID', $cmt->issue->project->proID)
                        ->where('userID', session()->get('userID'))
                        ->where('roleID', '<>', 0)
                        ->first();
        $comment = new Comment;
        $comment->cmtID = random_int(10000, 99999);
        $comment->comment = $request->comment;
        $comment->memID = $member->memID;
        $comment->issueID = $cmt->issueID;
        $comment->repCmt = $cmtID;
        $comment->save();
        if ($request->hasFile('cmt_files')) {
            $files = $request->file('cmt_files');
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $filename = random_int(100000, 999999) .'_' .$name;
                $file->storeAs('public/uploads/', $filename );
                $fullname = 'storage/uploads/' .$filename;
                $file = new Upload_file;
                $file->fileName = $filename;
                $file->issueID = $cmt->issueID;
                $file->cmtID =  $comment->cmtID;
                $file->save();
            }
        }
        // Thông báo
          $notification = new Notification;
          $notification->notiID = random_int(10000, 99999);
          $notification->userID = $cmt->member->userID;
          $notification->notiUrl = route('issue.show', ['issueID' => $cmt->issueID]);
          $notification->created_by = session()->get('userID');
          $notification->notiTypeID = 4;
          $notification->save();

        return redirect()->back();
    }

    // FILE
    public function uploads(Request $request, $issueID){
        if ($request->hasFile('uploadFile')) {
            $files = $request->file('uploadFile');
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $filename = random_int(100000, 999999) .'_' .$name;
                $file->storeAs('public/uploads/', $filename );
                $fullname = 'storage/uploads/' .$filename;
                $file = new Upload_file;
                $file->fileName = $filename;
                $file->issueID = $issueID;
                $file->save();
            }
        }
        return redirect()->back();
    }

    public function desFile($fileID){
        $upload_file = Upload_file::where('fileID', $fileID);
        $upload_file->delete();
        return redirect()->back();
    }

}
