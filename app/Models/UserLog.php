<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    //public $timestamps = false;
    protected $table = TBL_USER_LOGS;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title','status'];
}
