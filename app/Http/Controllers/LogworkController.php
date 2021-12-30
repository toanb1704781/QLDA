<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Issue;
use App\Models\Logwork;
use App\Models\Project;
use App\Models\Member;
use App\Models\Timeunit;
use App\Models\Date;
use App\Models\Location;

class LogworkController extends Controller
{
    private $pathView = 'admin.contents.logwork.logwork_';

    public function index(Request $request){
        $now = Date::getDateNow();
        $startOfWeek = Carbon::create($now)->startOfWeek()->toDateString();
        if (session()->get('userID') == 1) {
            $project = Project::where('statusID', '<>', 3)->get();
            if ($project->isNotEmpty()) {
                foreach ($project as $project) {
                    $data_proID[] = $project->proID;
                }
            }
        }else{
            $member = Member::where('roleID', 1)
                            ->where('userID', session()->get('userID'))
                            ->get();
            if ($member->isNotEmpty()) {
                foreach ($member as $member) {
                    $data_proID[] = $member->proID;
                }
            }else{
                return "Bạn chưa có dự án để xem báo cáo chấm công!!";
            }
        }
        $issue = Issue::whereIn('proID', $data_proID)->get();
         // Lấy dữ liệu logwork
         if ($issue->isNotEmpty()) {
            foreach ($issue as $issue) {
                $data_issueID[] = $issue->issueID;
            }
            $logwork = Logwork::whereIn('issueID', $data_issueID)
                                ->whereDate('created_at', '>=', $startOfWeek)
                                ->whereDate('created_at', '<=', $now)
                                ->orderBy('created_at')
                                ->get();
            $from = Carbon::create($startOfWeek)->day;
            $to = Carbon::create($now)->day;
                // Tính tổng
                $temp = $to - $from;
                for($i = 0; $i <= $to; $i++){
                    $date = Carbon::create($startOfWeek);
                    $date->addDay($i)->toDateTimeString();
                    $logwork_total = Logwork::whereIn('issueID', $data_issueID)
                                            ->whereDate('created_at', $date)
                                            ->get();
                    $tong = 0;
                    foreach ($logwork_total as $row) {
                        $tong += Timeunit::getTimeUnit($row->workingTime);
                    }
                    $data_total[$from + $i] = $tong;
                }
        }

        return view($this->pathView .'index', [
            'logwork' => $logwork ?? null,
            'from' => $from ?? null,
            'to' => $to ?? null,
            'valueProID' => $request->proID,
            'valueFrom' => $request->fromDate ?? $startOfWeek,
            'valueTo' => $request->toDate ?? $now,
            'data_total' => $data_total ?? null,
        ]);
    }

    public function filterLogwork(Request $request){
        $from = Carbon::create($request->fromDate)->day;
        $to = Carbon::create($request->toDate)->day;
        if ($request->proID == 'tat-ca' || $request->proID == null) {
            // Lấy dự án theo quyền
            if (session()->get('userID') == 1) {
                $project = Project::where('statusID', '<>', 3)->get();
                if ($project->isNotEmpty()) {
                    foreach ($project as $project) {
                        $data_proID[] = $project->proID;
                    }
                }
            }else{
                $member = Member::where('roleID', 1)
                                ->where('userID', session()->get('userID'))
                                ->get();
                if ($member->isNotEmpty()) {
                    foreach ($member as $member) {
                        $data_proID[] = $member->proID;
                    }
                    // $allMember = Member::whereIn('proID', $data_proID)
                    //                     ->whereNotIn('roleID', [4, 5])
                    //                     ->get();
                }
            }
          
            if ($request->memID != null) {
                $issue = Issue::whereIn('proID', $data_proID)
                                ->where('memID', $request->memID)
                                ->get();
                // $allMember = Member::whereNotIn('roleID', [4, 5])
                //                     ->get();
            }else{
                $issue = Issue::whereIn('proID', $data_proID)
                                ->get();
                // $allMember = Member::whereNotIn('roleID', [4, 5])
                //                     ->get();
            }
        }else{
            $allMember = Member::where('proID', $request->proID)
                            ->whereNotIn('roleID', [4, 5])
                            ->get();
            if ($request->memID != null) {
                $issue = Issue::where('proID', $request->proID)
                                ->where('memID', $request->memID)
                                ->get();
            }else{
                $issue = Issue::where('proID', $request->proID)
                                ->get();
            }
        }

        // Lấy dữ liệu logwork
        if ($issue->isNotEmpty()) {
            foreach ($issue as $issue) {
                $data_issueID[] = $issue->issueID;
            }
            $logwork = Logwork::whereIn('issueID', $data_issueID)
                                ->whereDate('created_at', '>=', $request->fromDate)
                                ->whereDate('created_at', '<=', $request->toDate)
                                ->orderBy('created_at')
                                ->get();
            // Tính tổng
            $temp = $to - $from;
            for($i = 0; $i <= $to; $i++){
                $date = Carbon::create($request->fromDate);
                $date->addDay($i)->toDateTimeString();
                $logwork_total = Logwork::whereIn('issueID', $data_issueID)
                                        ->whereDate('created_at', $date)
                                        ->get();
                $tong = 0;
                foreach ($logwork_total as $row) {
                    $tong += Timeunit::getTimeUnit($row->workingTime);
                }
                $data_total[$from + $i] = $tong;
            }
        }

        return view($this->pathView .'index', [
            'logwork' => $logwork ?? null,
            'allMember' => $allMember ?? null,
            'from' => $from ?? null,
            'to' => $to ?? null,
            'valueProID' => $request->proID,
            'valueMemID' => $request->memID,
            'valueFrom' => $request->fromDate,
            'valueTo' => $request->toDate,
            'data_total' => $data_total ?? null,
        ]);
    }

    public function store(Request $request){
        // Lấy đơn vị thời gian
        $issue = Issue::where('issueID', $request->issueID)->first();
        $old_logwork = Logwork::where('issueID', $request->issueID)
                                ->orderBy('timeRemaining' )
                                ->first();
        if (isset($old_logwork)) {
            $timeRemaining = $old_logwork->timeRemaining - Timeunit::getTimeUnit($request->workingTime);
            if ($timeRemaining < 0) {
                $timeRemaining = 0;
            }
        }else{
            $timeRemaining = Timeunit::getTimeUnit($issue->original_estimate) - Timeunit::getTimeUnit($request->workingTime);
        }

        // Lưu tọa độ vào CSDL
        $location = new Location;
        $location->locationID = random_int(10000, 99999);
        $location->longitude = $request->longitude;
        $location->latitude = $request->latitude;
        $location->save();

        $logwork = new Logwork;
        $logwork->logworkID = random_int(10000, 99999);
        $logwork->issueID = $request->issueID;
        $logwork->workingTime = $request->workingTime;
        $logwork->timeRemaining = $timeRemaining;
        $logwork->created_at = $request->created_at;
        $logwork->logworkNote = $request->logworkNote;
        $logwork->locationID = $location->locationID;
        $logwork->save();

        return redirect()->back();
    }

    // Lịch sử chấm công cá nhân
        public function myLogwork(Request $request){
            if ($request->toDate != null) {
                $member = Member::where('userID', session()->get('userID'))
                                ->whereNotIn('roleID', [4, 5])
                                ->get();
                if($member->isNotEmpty()){
                    foreach ($member as $mem) {
                        $data_memID[] = $mem->memID;
                    }
                    $issue = Issue::whereIn('memID', $data_memID)->get();
                    if($issue->isNotEmpty()){
                        foreach ($issue as $issue) {
                            $data_issueID[] = $issue->issueID;
                        }
                            $from = Carbon::create($request->fromDate)->day;
                            $to = Carbon::create($request->toDate)->day;
                            $logwork = Logwork::whereIn('issueID', $data_issueID)
                                                ->whereDate('created_at', '>=', $request->fromDate)
                                                ->whereDate('created_at', '<=', $request->toDate)
                                                ->orderBy('created_at')
                                                ->get();
                    }
                }
            }else{
                $now = Date::getDateNow();
                $startOfWeek = Carbon::create($now)->startOfWeek()->toDateString();
                $member = Member::where('userID', session()->get('userID'))
                                ->whereNotIn('roleID', [4, 5])
                                ->get();
                if($member->isNotEmpty()){
                    foreach ($member as $mem) {
                        $data_memID[] = $mem->memID;
                    }
                    $issue = Issue::whereIn('memID', $data_memID)->get();
                    if($issue->isNotEmpty()){
                        foreach ($issue as $issue) {
                            $data_issueID[] = $issue->issueID;
                        }
                            $from = Carbon::create($startOfWeek)->day;
                            $to = Carbon::create($now)->day;
                            $logwork = Logwork::whereIn('issueID', $data_issueID)
                                                ->whereDate('created_at', '>=', $startOfWeek)
                                                ->whereDate('created_at', '<=', $now)
                                                ->orderBy('created_at')
                                                ->get();
                    }else{
                        return "Bạn chưa có lịch sử chấm công";
                    }
                }
            }
            // Tính tổng
            if (isset($to) && isset($from)) {
                $temp = $to - $from;
                for($i = 0; $i <= $to; $i++){
                    $date = Carbon::create($request->fromDate ?? $startOfWeek);
                    $date->addDay($i)->toDateTimeString();
                    $logwork_total = Logwork::whereIn('issueID', $data_issueID)
                                            ->whereDate('created_at', $date)
                                            ->get();
                    $tong = 0;
                    foreach ($logwork_total as $row) {
                        $tong += Timeunit::getTimeUnit($row->workingTime);
                    }
                    $data_total[$from + $i] = $tong;
                }
            }

            return view($this->pathView .'myLogwork', [
                'logwork' => $logwork ?? null,
                'from' => $from ?? null,
                'to' => $to ?? null,
                'valueFrom' => $request->fromDate ?? $startOfWeek,
                'valueTo' => $request->toDate ?? $now,
                'data_total' => $data_total ?? null,
            ]);
        }

        public function putTimeunit(Request $request){
            if ($request->timeUnitDay != null) {
                Timeunit::where('unit', 'd')
                        ->update([
                            'timeUnit' => $request->timeUnitDay,
                        ]);
            }
            if ($request->timeUnitWeek != null) {
                Timeunit::where('unit', 'w')
                        ->update([
                            'timeUnit' => $request->timeUnitWeek,
                        ]);
            }
            return redirect()->back();
        }

    // Hiển thị trên bản đồ
    public function logworkLocation($locationID){
        $location = Location::where('locationID', $locationID)->first();
        return view('map', [
            'longitude' => $location->longitude,
            'latitude' => $location->latitude,
        ]);
    }
}
