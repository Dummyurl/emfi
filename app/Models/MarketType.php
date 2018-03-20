<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketType extends Model
{
    protected $table = 'market_type';

    public static function getArrayList()
    {
    	return self::orderBy("id")->pluck("market_name","id")->toArray();
    }
}
