<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsGraph extends Model
{
    protected $table = TBL_CMS_GRAPHS;

    protected $fillable = ['graph_name','cms_page_id','position','location'];

    public $timestamps = true;
}
