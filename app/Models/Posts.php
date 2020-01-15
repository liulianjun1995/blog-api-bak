<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    use SoftDeletes;

    public function tags()
    {
        return $this->belongsToMany(Tags::class, 'posts_tags', 'post_id', 'tag_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admins::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id');
    }

    public function praises()
    {
        return $this->hasMany(PostPraise::class, 'post_id');
    }
}
