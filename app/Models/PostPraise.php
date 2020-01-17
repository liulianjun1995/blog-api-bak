<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostPraise extends Model
{
    protected $fillable = [
        'user_id', 'post_id', 'ip'
    ];
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
