<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Member;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $pathView = 'admin.contents.user.user_';
    
    public function index()
    {
        $user = User::where('isActive', 1)
                    ->orderBy('username')
                    ->get();
        return view($this->pathView .'index',[
            'user' => $user,
            'i' => 1,
            'isActive' => 1,
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userID)
    {
        $user = User::where('userID', $userID)->first();
        $member = Member::where('userID', $userID)->get();
        return view($this->pathView .'info',[
            'user' => $user,
            'member' => $member,
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
    public function update(Request $request, $userID)
    {
        $user = User::where('userID', $userID)->first();
        $user->information()->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($userID)
    {
        User::where('userID', $userID)
            ->update([
                'isActive' => 2,
            ]);
        Member::where('userID', $userID)
                ->update([
                    'roleID' => 0,
                ]);
        return redirect()->back();
    }

    // Active account
    public function activeUser($userID){
        User::where('userID', $userID)
            ->update([
                'isActive' => 1,
            ]);
        return redirect()->back();
    }

    // Cấp lại mật khẩu
    public function rePass(Request $request, $userID){
        User::where('userID', $userID)
            ->update([
                'password' => bcrypt($request->password),
            ]);
        return redirect()->back();
    }

    // Filter Account
    public function filterAccount(Request $request)
    {
        $user = User::where('isActive', $request->isActive)
                    ->orderBy('username')
                    ->get();
        return view($this->pathView .'index',[
            'user' => $user,
            'i' => 1,
            'isActive' => $request->isActive,
        ]);
    }
}
