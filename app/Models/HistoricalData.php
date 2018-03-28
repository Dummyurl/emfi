<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalData extends Model
{
    protected $table 	= 'historical_data';
    public $timestamps 	= true;
    protected $fillable = ['security_id', 'bid_price', 'ask_price', 'last_price', 'low_price', 'high_price', 'net_change', 'percentage_change', 'created', 'rtg_sp', 'current_oecd_member_cor_class', 'market_size', 'volume'];
}
