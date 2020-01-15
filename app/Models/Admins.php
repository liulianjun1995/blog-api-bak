<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{
    protected $table = 'admin_users';

    public function posts()
    {
        return $this->hasMany(Posts::class, 'user_id', 'id');
    }
}
