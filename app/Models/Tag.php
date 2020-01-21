<?php

namespace App\Models;

use App\Enums\ArticleConstants;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'title', 'status'
    ];

    protected $hidden = ['pivot'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'posts_tags', 'tag_id', 'post_id');
    }

    public function getStatusAttribute($value)
    {
        return ArticleConstants::ARTICLE_TAG_STATUS[$value] ?? '';
    }
}
