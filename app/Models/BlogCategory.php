<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Cviebrock\EloquentSluggable\Sluggable;

class BlogCategory extends Model
{
    use Sluggable;

    /**
     * The database table used by the model
     *
     * @var string
     */
    //public $timestamps = false;
    protected $table = TBL_BLOG_CATEGORIES;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title','status'];

    /**
     * Set or unset the timestamps for the model
     *
     * @var bool
     */
    public $timestamps = true;
    
   /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
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
    

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | For more information pleas check out the official Laravel docs at
    | http://laravel.com/docs/5.4/eloquent#relationships
    |
    */

    
}
