<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = "notifications";
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'userID');
    }

    public function notification_type()
    {
        return $this->belongsTo('App\Models\Notification_type', 'notiTypeID', 'notiTypeID');
    }
    

    public static function getNotifications(){
        $notification = Notification::where('userID', session()->get('userID'))
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        return $notification;
    }
}
