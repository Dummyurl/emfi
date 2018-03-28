<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Securities extends Model
{
    protected $table = 'securities';
    public $timestamps = true;
    protected $fillable = [
       'CUSIP','benchmark_family', 'market_id', 'country', 'country_id', 'ticker', 'benchmark', 'cpn', 'security_name', 'maturity_date', 'dur_adj_mid', 'bid_price', 'ask_price', 'last_price', 'low_price', 'high_price', 'yld_ytm_mid', 'z_sprd_mid', 'net_change', 'percentage_change', 'created', 'default', 'rtg_sp', 'current_oecd_member_cor_class', 'market_size', 'volume'
    ];
}
