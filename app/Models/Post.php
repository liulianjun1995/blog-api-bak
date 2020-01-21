<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'abstract', 'user_id', 'category_id', 'status', 'cover', 'content'
    ];

    /**
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'post_id', 'tag_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id');
    }

    public function praises()
    {
        return $this->hasMany(PostPraise::class, 'post_id');
    }

    public function syncTags($tags)
    {
        $_tags = $this->tags()->pluck('tags.id')->toArray();

        $deletes = array_diff($_tags, $tags);

        $adds = array_diff($tags, $_tags);

        $this->tags()->detach($deletes);

        $this->tags()->saveMany(Tag::query()->whereIn('id', $adds)->get());
    }
}
