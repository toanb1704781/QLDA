<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\carbon;
use App\Models\Member;
use App\Models\Project;
use App\Models\Comment;
use App\Models\Date;
use App\Models\Issue;
use App\Models\Timeunit;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// =================== Admin route ========================
Route::group(['prefix' => '/'], function() {
    
    // ========= Quản lý dự án =================
    Route::get('/', 'HomeController@index')->name('home.index')->middleware('checkLogin');
    
    Route::resource('/quanlyduan', 'ProjectController')->middleware('checkLogin');
    Route::get('/du-an-da-huy', 'ProjectController@delProjectIndex')->name('delProject.index')->middleware('checkLogin');
    Route::put('huy-du-an-{proID}', 'ProjectController@delProject')->name('delProject');
    Route::put('xoa-{memID}-khoi-du-an-{proID}', 'ProjectController@removePro')->name('removePro');
  
    

    // Khôi phục dự án
    Route::put('khoi-phuc-du-an-{proID}', 'ProjectController@restoreProject')->name('restoreProject');

    // Xóa dự án
    Route::delete('xoa-du-an-{proID}', 'ProjectController@deleteProject')->name('deleteProject');
    

        // ============ Thiết lập dự án ====================================
        Route::group(['prefix' => 'du-an/thiet-lap'], function() {
            Route::get('chi-tiet-{proID}', 'ProjectSettingController@getSettingDetails')->name('getSettingDetails')->middleware('checkLogin');
            Route::put('cap-nhat-{proID}', 'ProjectSettingController@putSettingDetails')->name('putSettingDetails');
            Route::get('thanh-vien-{proID}', 'ProjectSettingController@getSettingMember')->name('getSettingMember')->middleware('checkLogin');
            Route::post('them-thanh-vien', 'ProjectSettingController@postSettingMember')->name('postSettingMember');
            Route::put('default-assignee-{proID}-{memID}', 'ProjectSettingController@putSetDefaultAssignee')->name('putSetDefaultAssignee');
            
        });
        

  

    // ======== Quản lý Issue =============================
        Route::get('cong-viec', 'IssueController@myIssue')->name('myIssue');
        Route::get('du-an-{proID}', 'IssueController@index')->name('issue.index');
        Route::post('du-an-{proID}', 'IssueController@store')->name('issue.store');
        Route::get('cong-viec-{issueID}', 'IssueController@show')->name('issue.show');
        Route::put('issue/{issueID}/workflow', 'IssueController@workflow')->name('workflow');
        Route::get('loc-cong-viec-{proID}', 'IssueController@filterIssue')->name('filterIssue');
        Route::put('cap-nhat-issue-{issueID}', 'IssueController@update')->name('issue.update');
        Route::post('chia-se-issue-{issueID}', 'IssueController@postShareIssue')->name('postShareIssue');

        // Bình luận
            route::put('binh-luan-{issueID}', 'IssueController@comment')->name('issue.comment');
            route::put('cap-nhat/binh-luan-{cmtID}', 'IssueController@udpComment')->name('comment.update');
            route::delete('xoa/binh-luan-{cmtID}', 'IssueController@desComment')->name('comment.destroy');
            route::put('upload-file-{issueID}', 'IssueController@uploads')->name('issue.uploads');
            route::delete('xoa/file-{fileID}', 'IssueController@desFile')->name('file.destroy');
            route::post('tra-loi-binh-luan-{cmtID}', 'IssueController@repComment')->name('repComment');
        
        // Log work
            Route::get('lich-su-cham-cong', 'LogworkController@myLogwork')->name('logwork.myLogwork');
            Route::get('bao-cao-cham-cong', 'LogworkController@index')->name('logwork.index');
            Route::post('log-work', 'LogworkController@store')->name('logwork.store');
            Route::get('du-an/bao-cao-cham-cong', 'LogworkController@filterLogwork')->name('logwork.filter');
            Route::put('putTimeunit', 'LogworkController@putTimeunit')->name('logwork.putTimeunit');
            // Hiển thị tọa độ
            Route::get('vi-tri-cham-cong-{locationID}', 'LogworkController@logworkLocation')->name('logwork.logworkLocation');

    // ========== Quản lý tài khoản ===============
    Route::resource('/quanlytaikhoan', 'AccountController')->middleware('checkLogin');
    Route::put('kich-hoat-{userID}', 'AccountController@activeUser')->name('activeUser');
    Route::put('vo-hieu-hoa-{userID}', 'AccountController@disable')->name('disable');
    Route::put('cap-lai-mat-khau-{userID}', 'AccountController@rePass')->name('rePass');
    Route::get('loc-tai-khoan/', 'AccountController@filterAccount')->name('filterAccount')->middleware('checkLogin');

    
    Route::get('test', function() {
        $issue = issue::where('issueID', 17347)->first();
        $now = Carbon::now()->addHour(7);
        $time = Carbon::create($issue->created_at);
        $test =  $now->diffInMinutes($time, false);
        return view('test', [
            'now' => $now,
            'time' => $time,
            // 'test' => $test,
        ]);
    });

    
    Route::get('map', function() {
        return view('map');
    });
    
    
});

    // Đăng ký, đăng nhập, đăng xuất
    $HomeController = 'HomeController@';
    Route::get('dangnhap', $HomeController. 'getLogin')->name('getLogin');
    Route::post('dangnhap', $HomeController.'postLogin')->name('postLogin');
    Route::get('dangky', $HomeController.'getRegister')->name('getRegister');
    Route::post('dangky', $HomeController.'postRegister')->name('postRegister');
    Route::get('dangxuat', $HomeController.'logout')->name('logout');

    


