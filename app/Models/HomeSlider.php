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

    public static function getHomeSliders($country)
    {
        $sliders = HomeSlider::select(TBL_HOME_SLIDER.".*",TBL_POST.".description as post_description",TBL_SECURITY.".CUSIP as graph_title")
                ->leftJoin(TBL_SECURITY,TBL_SECURITY.".id","=",TBL_HOME_SLIDER.".security_id")
                ->leftJoin(TBL_POST,TBL_POST.".id","=",TBL_HOME_SLIDER.".post_id")
                ->leftJoin(TBL_COUNTRY,TBL_COUNTRY.".id","=",TBL_HOME_SLIDER.".country_id")
                ->where(TBL_HOME_SLIDER.'.status',1)
                ->where(function($sliders) use ($country)
                {    
                    $sliders->where(TBL_HOME_SLIDER.".country_id",$country);
                    $sliders->orWhere(TBL_HOME_SLIDER.".country_id",'=',NULL);
                })
                ->orderBy(TBL_HOME_SLIDER.'.order')
                ->get();
        return $sliders;
    }
}
