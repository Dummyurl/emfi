<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickers extends Model
{
    protected $table = 'tickers';
    public $timestamps = true;
    protected $fillable = ['ticker_name', 'ticker_type', 'created_at', 'updated_at'];
}
