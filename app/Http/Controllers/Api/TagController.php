<?php

namespace App\Http\Controllers\Api;

use App\Models\Tags;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends ApiController
{
    public function articles(Request $request, Tags $tags, $name = '')
    {
        $fields = ['posts.id', 'posts.id as token', 'posts.title', 'posts.abstract', 'posts.category_id', 'posts.cover', 'posts.content', 'posts.is_recommend', 'posts.is_top', 'posts.view_count', 'posts.created_at'];
        if ($tag  = $tags->query()->where('title', $name)->first()) {
            $list = $tag->posts()->select($fields)->withCount('comments')->with(['tags:title', 'category:id,title'])->paginate(10);
            $list->each(function ($item) {
                $item->token = encode_id($item->token);
                $item->cover = \Storage::disk(config('admin.upload.disk'))->url($item->cover);
                $item->create_time = $item->created_at->format('Y-m-d');
                unset($item->created_at);
                unset($item->id);
                unset($item->category_id);
            });
            return $this->success($list);
        }
        return $this->error();
    }
}
