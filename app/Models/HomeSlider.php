<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['title', 'description'];
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
    protected $fillable = ['post_id','security_id','country_id','graph_type','graph_period','post_description','post_title','slider_type','status','order','option_maturity','option_price'];
    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps = true;

    public static function getHomeSliders($country)
    {
        $sliders = HomeSlider::select(TBL_HOME_SLIDER.".*",TBL_SECURITY.".security_name as graph_title",TBL_COUNTRY.".title as country_name")
                ->leftJoin(TBL_SECURITY,TBL_SECURITY.".id","=",TBL_HOME_SLIDER.".security_id")
                ->leftJoin(TBL_COUNTRY,TBL_COUNTRY.".id","=",TBL_HOME_SLIDER.".country_id")
                ->where(TBL_HOME_SLIDER.'.status',1)
                // ->where(function($sliders) use ($country)
                // {
                //     $sliders->where(TBL_HOME_SLIDER.".country_id",$country);
                //     $sliders->orWhere(TBL_HOME_SLIDER.".country_id",'=',NULL);
                //     $sliders->orWhere(TBL_HOME_SLIDER.".country_id",'=',0);
                // })
                ->orderBy(TBL_HOME_SLIDER.'.order')
                ->get();
        return $sliders;
    }

	public static function getMaxOrder()
    {
        $orderMax = HomeSlider::max('order');
		$orderMax = $orderMax + 1;
        return $orderMax;
    }


    public static function getGraphTypes(){
      $array = [
                        // 'line'=>'Line Graph',
                        'market_movers_gainers'=> 'Market Movers Gainers',
                        'market_movers_laggers'=> 'Market Movers Laggers',
                        'market_history'=> 'Market History',
                        'yield_curve'=>'Yield Curve (Scatter)', 
                        'differential'=> 'Differential', 
                        'regression'=> 'Regression',
                        'relative_value'=> 'Relative Value',
                    ];
          return $array;

    }

    public static function getDuration()
    {
        $array = [
                1=>'Maturity',
                2=>'Duration',
                ];
      return $array;   
    }
    public static function getPrices()
    {
       $array = [
                1=>'Price',
                2=>'Yield',
                3=>'Spread',
                ];
      return $array; 
    }
    public static function getRatings()
    {
       $array = [
                1 => 'Rating',
                0 => 'OECD'
                ];
      return $array; 
    }
    public static function getCredits()
    {
       $array = [
                5 => 'Credit',
                1 => 'Equities'
                    ];
          return $array;

    }


}
