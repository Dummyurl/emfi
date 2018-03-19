<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostTag extends Model
{
    public $timestamps = false;
    protected $table = TBL_BLOG_POST_TAG;
    /**
     * @var array
     */
    protected $fillable = ['post_id', 'tag_id'];

    public function post()
    {
        return $this->belongsTo(\App\Models\BlogPost::class);
    }
}
