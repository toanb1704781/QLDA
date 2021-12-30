<?php

use Illuminate\Database\Seeder;
use App\Models\Information;
use App\Models\User;
use App\Models\Role;
use App\Models\Workflow;
use App\Models\Project_status;
use App\Models\Issue_type;
use App\Models\Priority;
use App\Models\Timeunit;
use App\Models\Notification_type;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin Seeder
        $information = new Information;
        $information->infoID = 1;
        $information->save();

        $user = new User;
        $user->username = 'admin';
        $user->password = bcrypt('123');
        $user->infoID = 1;
        $user->isActive = 1;
        $user->save();

        // Role Seeder
        $role_arr = array("4" => "Empty", "1" => "Project Manager", "2" => "Developer", "3" => "Tester", "5" => "Viewer");
        foreach ($role_arr as $key => $value) {
            $role = new Role;
            $role->roleID = $key;
            $role->role = $value;
            $role->save();
        }

        // Workflow Seeder
        $workflow_arr = array('Open', 'In Progress', 'Resolved', 'Re-open', 'Closed');
        foreach ($workflow_arr as $value) {
            $workflow = new Workflow;
            $workflow->workflow = $value;
            $workflow->save();
        }

        // Project_Status Seeder
        $proStatus_arr = array('Đang thực hiện', 'Đã hoàn thành', 'Đã hủy');
        foreach ($proStatus_arr as $value) {
            $project_status = new Project_status;
            $project_status->status = $value;
            $project_status->save();
        }

        // Issue_type Seeder
        $type_arr = array("Task" => 3, "Bug" => 4);
        foreach ($type_arr as $key => $value) {
            $issue_type = new Issue_type;
            $issue_type->typeID = $value;
            $issue_type->issue_type = $key;
            $issue_type->save();
        }

        // Priority Seeder
        $priority_arr = array('Cao', 'Trung bình', 'Thấp');
        foreach ($priority_arr as $value) {
            $priority = new Priority;
            $priority->priority = $value;
            $priority->save();
        }

        // Timeunit Seeder
        $arrTimeUnit = array("h" => 1, "d" => 8, "w" => 46);
        foreach ($arrTimeUnit as $key => $value) {
            $timeunit = new Timeunit;
            $timeunit->unit = $key;
            $timeunit->timeunit = $value;
            $timeunit->save();
        }

        // Notification type Seeder
        $arrNotiType = array("đã thêm bạn vào một dự án", "đã phân công cho bạn một công việc", "đã chia sẽ một công việc với bạn", "đã nhắc tới bạn trong một bình luận");
        foreach ($arrNotiType as $value) {
            $notiType = new Notification_type;
            $notiType->notiType = $value;
            $notiType->save();
        }
        
    }
}
