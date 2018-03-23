<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

     /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = TBL_POST;
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title','slug','description'];
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
