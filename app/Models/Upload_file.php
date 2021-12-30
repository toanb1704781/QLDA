<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload_file extends Model
{
    public $timestamps = false;
    protected $table = 'upload_files';

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment', 'cmtID', 'cmtID');
    }
}
