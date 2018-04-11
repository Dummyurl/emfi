<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Country extends Model
{
    use Sluggable;
    protected $fillable = ['title','country_code', 'country_type'];
    protected $table = TBL_COUNTRY;

    public function sluggable()
    {
        return 
        [
            'slug' => 
            [
                'source' => 'title',
                'on_update' => true
            ]
        ];
    }      

    public static function getCountryList()
    {
    	$data = array();
    	$rows = Country::orderBy('title')->get();
    	if($rows)
    	{
    		foreach ($rows as $cn)
    		{
    			$data[$cn->id] = $cn->title; 
    		}
    	}
    	return $data;
    }
}
