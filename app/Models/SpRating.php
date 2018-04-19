<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpRating extends Model
{
    protected $table = 'sp_rating';
    public $timestamps = false;
    protected $fillable = ['sp_name', 'sp_order'];
}
