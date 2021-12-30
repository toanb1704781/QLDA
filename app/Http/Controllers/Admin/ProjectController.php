<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Priority;
use App\Models\Date;
use App\Models\Project_status;
use App\Models\Member;
use App\Models\Role;
use App\Models\Workflow;
use App\Models\Issue;
use App\Models\Comment;
use App\Models\Access;
use Carbon\Carbon;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $pathView = 'admin.contents.project.project_';

    public function index()
    {
        $userID = session()->get('userID');
        if ($userID == 1) {
            $project = Project::where('statusID', '<>', 3)
                            // ->orderByDesc('created_at')
                            ->get();
        }else{
            $member = Member::where('userID', $userID)
                            ->where('roleID', '<>', 0)
                            ->where('roleID', '<>', 5)
                            ->get();
            if(!$member->isEmpty()){
                foreach ($member as $mem) {
                    $arr_proID[] = $mem->proID;
                }
                $project = Project::whereIn('proID', $arr_proID)->get();
            }else{
                $project = [];
            }
        }

        return view($this->pathView .'index', [
            'project' => $project,
            'userID' => $userID,
            'i' => 1,
        ]);
    }

     // Danh sách các dự án hủy
     public function delProjectIndex(){
        $userID = session()->get('userID');
        if ($userID == 1) {
            $project = Project::where('statusID', 3)
                            ->orderByDesc('created_at')
                            ->get();
        }else{
            $project = Member::where('userID', $userID)
                            ->get();
        }

        return view($this->pathView .'delProject', [
            'project' => $project,
            'userID' => $userID,
            'i' => 1,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $date = new Date;
        $date->dateID = random_int(10000, 99999);
        $date->startDate = $request->startDate;
        $date->save();

        $project = new Project;
        $project->proID = random_int(1000, 9999);
        $project->proName = $request->proName;
        $project->proKey = $request->proKey;
        $project->proDesc = $request->proDesc;
        $project->dateID =  $date->dateID;
        $project->statusID =  1;
        $project->save();

        $member = new Member;
        $member->memID = random_int(1000, 9999);
        $member->proID = $project->proID;
        $member->roleID = 1;
        $member->userID = session()->get('userID');
        $member->save();
        
        return redirect()->route('quanlyduan.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $proID)
    {
        $project = Project::where('proId', $proID)->first();
        if ($request->selectedIssue != 0) {
            $issueDetail = Issue::where('issueID', $request->selectedIssue)->first();
            $comment = Comment::where('issueID', $request->selectedIssue)
                            ->where('repCmt', null)
                            ->get();
            $upload_file = $issueDetail->upload_file;

            // Tạo truy cập issue gần nhất
            $access_old = Access::where('issueID', $request->selectedIssue)
                                ->where('userID', session()->get('userID')) 
                                ->first();
            if (isset($access_old)) {
                Access::where('issueID', $request->selectedIssue)
                        ->where('userID', session()->get('userID'))
                        ->update([
                            'created_at' => Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                        ]);
            }else{
                $access = new Access;
                $access->accessID = random_int(10000, 99999);
                $access->userID = session()->get('userID');
                $access->issueID = $request->selectedIssue;
                $access->save();
            }
        }

        $issue = Issue::where('proID', $proID)
                        ->orderBy('created_at')
                        ->get();
        $member = Member::where('proID', $proID)
                        ->where('roleID', '<>', 0)
                        ->where('roleID', '<>', 5)
                        ->get();
        $workflow = Workflow::all();
        $priority = Priority::all();
        // Tạo truy cập project gần nhất
        $access_old = Access::where('proID', $proID)
                            ->where('userID', session()->get('userID')) 
                            ->first();
        if (isset($access_old)) {
            Access::where('proID', $proID)
                    ->where('userID', session()->get('userID'))
                    ->update([
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                    ]);
        }else{
            $access = new Access;
            $access->accessID = random_int(10000, 99999);
            $access->userID = session()->get('userID');
            $access->proID = $proID;
            $access->save();
        }


        return view($this->pathView .'show', [
            'project' => $project,
            'issue' => $issue,
            'member' => $member,
            'workflow' => $workflow,
            'priority' => $priority,
            'value' => 6,
            'issueDetail' => $issueDetail ?? null,
            'comment' => $comment ?? null,
            'upload_file' => $upload_file ?? null,
            'i' => 1,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $proID)
    {
        Project::where('proID', $proID)
                ->update([
                    'proName' => $request->proName,
                    'proKey' => $request->proKey,
                    'proDesc' => $request->proDesc,
                ]);
                
        $upd_pro = Project::where('proID', $proID)->first();
        $i = 0;
        foreach ($upd_pro->issue->sortBy('created_at') as $issue) {
            $i++;
            Issue::where('issueID', $issue->issueID)
                    ->update([
                        'issueKey' => $request->proKey .'-'. $i,
                    ]);
            
        }
        Date::where('dateID', $upd_pro->dateID)
                ->update([
                    'startDate' => $request->startDate,
                ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delProject($proID){
        Project::where('proID', $proID)
            ->update([
                'statusID' => 3,
            ]);
        return redirect()->route('quanlyduan.index');
    }

    // Xóa khỏi dự án
    public function removePro($memID, $proID){
        Member::where('memID', $memID)
                ->update([
                    'roleID' => 0,
                ]);
        Issue::where('memID', $memID)
            ->where('proID', $proID)
            ->update([
                'memID' => null,
            ]);
        
        return redirect()->back();
    }

    // Khôi phục dự án
    public function restoreProject($proID){
        Project::where('proID', $proID)
                ->update([
                    'statusID' => 1,
                ]);
        return redirect()->back(); 
    }

    // Xóa vĩnh viễn dự án
    public function deleteProject($proID){
        $project = Project::where('proID', $proID);
        $project->delete();
        return redirect()->back(); 
    }
}
