<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['title','country_code'];

       protected $table = TBL_COUNTRY;
}
