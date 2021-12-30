<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\Notification;

class ProjectSettingController extends Controller
{
    private $pathView = 'admin.contents.project.setting.proSet_';

    public function getSettingDetails($proID)
    {
        $project = Project::where('proID', $proID)->first();
        $user = User::where('userID', '<>', 1)
                    ->where('isActive', 1)
                    ->get();
        $role = Role::all();
        return view($this->pathView .'details', [
            'pro' => $project,
            'user' => $user,
            'role' => $role,
        ]);
    }

    public function putSettingDetails($proID){
        Project::where('proID', $proID)
                ->update([
                    'proName' => $request->proName,
                    'proDesc' => $request->proDesc,
                ]);
        $upd_pro = Project::where('proID', $proID)->first();
        Date::where('dateID', $upd_pro->dateID)
                ->update([
                    'startDate' => $request->startDate,
                ]);
        return redirect()->back();
    }

    public function getSettingMember($proID){
        $project = Project::where('proID', $proID)->first();
        $member = Member::where('proID', $proID)
                        ->where('roleID' , '<>', 0)
                        ->get();
        foreach ($member as $mem) {
            $data_member[] = $mem->userID;
        }
        $user = User::where('userID', '<>', 1)
                    ->where('isActive', 1)
                    ->whereNotIn('userID', $data_member)
                    ->orderBy('username')
                    ->get();
        $role = Role::whereNotIn('roleID' , [4, 5])
                    ->get();
        return view($this->pathView .'getSettingMember', [
            'user' => $user,
            'role' => $role,
            'pro' => $project,
            'member' => $member,
        ]);
    }

    public function postSettingMember(Request $request){
        $member = new Member;
        $member->memID = random_int(10000, 99999);
        $member->userID = $request->userID;
        $member->proID = $request->proID;
        $member->roleID = $request->roleID;
        $member->save();

        // Thông báo
            $notification = new Notification;
            $notification->notiID = random_int(10000, 99999);
            $notification->userID = $request->userID;
            $notification->notiUrl = route('quanlyduan.show', ['quanlyduan' =>  $request->proID]);
            $notification->created_by = session()->get('userID');
            $notification->notiTypeID = 1;
            $notification->save();

        return redirect()->back();
    }

    // Set Default Assignee
    public function putSetDefaultAssignee($proID, $memID){
        Project::where('proID', $proID)
                ->update([
                    'default_assignee' => $memID,
                ]);
        return redirect()->back();
    }
}
