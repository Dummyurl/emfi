<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class BlogPost extends Model
{
    use SoftDeletes,Sluggable;

    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = TBL_BLOG_POST;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title','status','category_id','short_description','content'];

    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps = true;
 
 
    public function category()
    {
        return $this->belongsTo(\App\Models\BlogCategory::class)->withTrashed();
    } 

    function getTags($onlyIDS = 0)
    {
        $id = $this->id;

        $query = DB::table(TBL_BLOG_POST_TAG)
                ->where("post_id",$id)
                ->get();

        if($onlyIDS == 1)
        {
            $arr = array();
            foreach($query as $row)
            {   
                $arr[] = $row->tag_id;
            }
            return $arr;
        }        
        else
        {
            return $query;        
        }             
        
    }

    public function sluggable()
    {
        return [
            'slug' => 
            [
                'source' => 'title',
                'on_update' => true
            ]
        ];
    }  
}
