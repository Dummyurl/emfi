<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = TBL_HOME_SLIDER;
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['post_id','security_id','country_id','graph_type','slider_type','status','order'];
    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps = true;
}
