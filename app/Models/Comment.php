<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Comment;
use App\Models\User;

class Comment extends Model
{
    public $timestamps = false;
    
    public function issue()
    {
        return $this->belongsTo('App\Models\Issue', 'issueID', 'issueID');
    }

    public function member()
    {
        return $this->belongsTo('App\Models\Member', 'memID', 'memID');
    }

    public function upload_file()
    {
        return $this->hasMany('App\Models\Upload_file', 'cmtID', 'cmtID');
    }
    
    
    public function getTime($created_at){
        Carbon::setLocale('vi');
        $time = Carbon::create($created_at);
        $now = Carbon::now();
        $now->addHour(7);
        return $time->diffForHumans($now);
    }

    public function getRepCmt($cmtID){
        $comment = Comment::where('repCmt', $cmtID)
                        ->orderBy('created_at')
                        ->get();
        return $comment;
    }

    public function getTagUsername($userID){
        $user = User::where('userID', $userID)->first();
        return $user->username;
    }

    public static function bell(){
        $user = user::where('userID', session()->get('userID'))->first();
        $comment = comment::where('tag', $user->username)
                            ->orderBy('created_at', 'desc')
                            ->get();
        return $comment;
    }
    
}
