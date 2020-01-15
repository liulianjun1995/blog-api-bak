<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{

    protected $fillable = [
        'user_id', 'post_id', 'content', 'parent_id', 'root_id', 'show'
    ];

    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
