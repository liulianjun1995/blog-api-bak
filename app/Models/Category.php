<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categorys';

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id');
    }
}
