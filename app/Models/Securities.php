<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Securities extends Model
{
    protected $table = 'securities';
    public $timestamps = false;
    protected $fillable = [
       'CUSIP', 'market_id', 'country', 'ticker', 'benchmark', 'cpn', 'security_name', 'maturity_date', 'dur_adj_mid', 'bid_price', 'ask_price', 'last_price', 'low_price', 'high_price', 'yld_ytm_mid', 'z_sprd_mid', 'net_change', 'percentage_change'
    ];
}
