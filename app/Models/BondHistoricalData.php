<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BondHistoricalData extends Model
{
    protected $table 	= 'bond_historical_data';
    public $timestamps 	= true;
    protected $fillable = ['security_id', 'bid_price', 'DUR_ADJ_MID', 'YLD_YTM_MID', 'Z_SPRD_MID', 'ask_price', 'last_price', 'low_price', 'high_price', 'net_change', 'percentage_change', 'created', 'rtg_sp', 'current_oecd_member_cor_class', 'market_size', 'volume'];

}
