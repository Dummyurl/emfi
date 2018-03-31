<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickers extends Model
{
    protected $table = 'tickers';
    public $timestamps = true;
    protected $fillable = ['ticker_name', 'ticker_type', 'country_id', 'market_id',  'created_at', 'updated_at'];
    public static function getCountriesList()
    {
    	$sql = "
				SELECT country_id,countries.title as country_title,ticker_name,tickers.id as main_id,tickers.ticker_type
				FROM tickers
				JOIN countries ON tickers.country_id = countries.id
				WHERE tickers.market_id = 5
				ORDER BY countries.title;    	
    	";

    	$rows = \DB::select($sql);

    	return json_decode(json_encode($rows),1);
    }
    
}
