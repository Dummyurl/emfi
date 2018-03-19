<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;


class CmsPage extends Model
{
    use Sluggable;

     /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = TBL_CMS_PAGES;
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title','page_title','meta_title','meta_description','short_description','description'];
    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps = true;
    
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
