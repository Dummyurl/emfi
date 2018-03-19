<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GraphSlider extends Model
{
    protected $table = TBL_CMS_GRAPH_SLIDERS;

     protected $fillable = ['graph_id','en_title','en_description','date','sn_title','sn_description'];

    public $timestamps = true;
}
