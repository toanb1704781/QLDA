<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\Project;
use App\Models\Issue;
use App\Models\User;
use App\Models\Member;
use App\Models\Information;
use App\Models\Issue_type;
use App\Models\Access;

class HomeController extends Controller
{
    public function index(){
        $member = Member::where('userID', session()->get('userID'))
                        ->whereNotIn('roleID', [4, 5])
                        ->get();
        if($member->isNotEmpty()){
            foreach ($member as $mem) {
                $data_memID[] = $mem->memID;
            }
            $issue = Issue::whereIn('memID', $data_memID)->get();
        }
        $access = Access::where('userID', session()->get('userID'))
                        ->where('proID', '<>', null)
                        ->orderBy('created_at', 'desc')
                        ->paginate(4);

        return view('index', [
            'issue' => $issue ?? null,
            'access' => $access ?? null
        ]);
    }

    // ================ Đăng nhập ==============================
    public function getLogin(){
        return view('login');
    }

    public function postLogin(Request $request){
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password])){
            if(auth::user()->isActive == 1 ){
                $userID = Auth::user()->userID;
                session()->put('userID', $userID);
                return redirect()->route('home.index');
            }
            if (auth::user()->isActive == 0) {
                return "Tài khoản của bạn chưa được kích hoạt, vui lòng chờ...";
            }
            if (auth::user()->isActive == 2) {
                return "Tài khoản của bạn đã bị khóa, liên hệ với người quản trị để mở lại tài khoản...";
            }
        }else{
            return redirect()->route('getLogin');
        }
    }

    // ================= Đăng ký ============================
    public function getRegister(Request $request){
         return view('register');
    }

    public function postRegister(Request $request){
        $info = new Information;
        $info->infoID = random_int(1000, 9999);
        $info->save();

        $user = new User;
        $user->userID = random_int(1000, 9999);
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->infoID = $info->infoID;
        $user->isActive = $request->isActive;
        $user->save();
        if($request->isActive == 0){
            return "Bạn đã đăng ký thành công, vui lòng chờ admin kích hoạt tài khoản...";
        }else{
            return redirect()->back();
        }
    }

    // ============= Đăng xuất ========================
    public function logout(){
        session()->flush();
        Auth::logout();
        return redirect()->route('getLogin');
    }

    public function activeUser($userID){
        User::where('userID', $userID)
            ->update([
                'isActive' => 1,
            ]);
        return redirect()->route('nguoidung.index');
    }

    public function delUser($userID){
        $user = User::where('userID', $userID);
        $user->delete();
        return redirect()->route('nguoidung.index');
    }

    public function vohieuhoa($userID){
        User::where('userID', $userID)
            ->update([
                'isActive' => 3,
            ]);
        return redirect()->route('nguoidung.index');
    }
}
