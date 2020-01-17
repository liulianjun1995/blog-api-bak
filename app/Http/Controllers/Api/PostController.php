<?php

namespace App\Http\Controllers\Api;

use App\Models\Comments;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class PostController extends ApiController
{
    public function index(Request $request, Post $posts)
    {
        $fields = ['id', 'id as token', 'title', 'abstract', 'category_id', 'cover', 'content', 'is_recommend', 'is_top', 'view_count', 'created_at'];
        $list =  $posts->select($fields)->withCount('comments')->withCount('praises')
            ->with(['tags:title', 'category:id,title'])->latest()->paginate(10);
        $list->each(function ($item) {
           $item->token = encode_id($item->token);
           $item->cover = Storage::disk(config('admin.upload.disk'))->url($item->cover);
           $item->create_time = $item->created_at->format('Y-m-d');
           unset($item->created_at);
           unset($item->id);
        });
        $list = $list->toArray();
        $list['comment_count'] = Comments::query()->count();
        return $this->success($list);
    }

    public function top(Request $request, Post $posts)
    {
        if (Redis::connection()->exists('top_posts')) {
            $list = json_decode(Redis::connection()->get('top_posts'));
        } else {
            $fields = ['id', 'id as token', 'title', 'abstract', 'category_id', 'cover', 'content', 'is_recommend', 'is_top', 'view_count', 'created_at'];
            $list =  $posts->select($fields)
                ->withCount('comments')
                ->withCount('praises')
                ->with(['tags:title', 'category:id,title'])
                ->where('is_top', 1)
                ->orderBy('created_at', 'desc')
                ->take(2)
                ->get();
            $list->each(function ($item) {
                $item->token = encode_id($item->token);
                $item->cover = Storage::disk(config('admin.upload.disk'))->url($item->cover);
                $item->create_time = $item->created_at->format('Y-m-d');
                unset($item->created_at);
                unset($item->id);
            });
            Redis::connection()->set('top_posts', json_encode($list), 'ex', 6 *60 *60);
        }
        return $this->success($list);
    }

    public function recommend(Request $request, Post $posts)
    {
        if (Redis::connection()->exists('recommend_posts')) {
            $list = json_decode(Redis::connection()->get('recommend_posts'));
        } else {
            $fields = ['id as token', 'title'];
            $list =  $posts->query()->where('is_recommend', 1)
                ->orderBy('created_at', 'desc')
                ->take(8)
                ->get($fields);
            $list->each(function ($item) {
                $item->token = encode_id($item->token);
            });
            Redis::connection()->set('recommend_posts', json_encode($list), 'ex', 6 *60 *60);
        }
        return $this->success($list);
    }

    public function hot(Request $request, Post $posts)
    {
        if (Redis::connection()->exists('hot_posts')) {
            $list = json_decode(Redis::connection()->get('hot_posts'));
        } else {
            $fields = ['id as token', 'title'];
            $list =  $posts->query()
                ->orderBy('view_count', 'desc')
                ->take(8)
                ->get($fields);
            $list->each(function ($item) {
                $item->token = encode_id($item->token);
            });
            Redis::connection()->set('hot_posts', json_encode($list), 'ex', 6 *60 *60);
        }
        return $this->success($list);
    }

    public function show(Request $request, Post $posts, $token)
    {
        $id = decode_id($token);
        $fields = ['id', 'id as token', 'title', 'abstract', 'category_id', 'cover', 'content', 'is_recommend', 'is_top', 'view_count', 'created_at'];
        if ($id <= 0 || !($info = $posts->query()->select($fields)->withCount('comments')->withCount('praises')->with(['tags:title', 'category:id,title'])->find($id))) {
            return $this->error('文章不存在');
        }
        $info->token = encode_id($info->token);
        $info->increment('view_count');

        $info->comments = $info->comments()->with(['user:id,id as token,avatar,nickname'])->where('show', 1)->get(['id', 'id as token', 'parent_id as parent', 'root_id as root', 'content', 'user_id', 'created_at'])->toArray();

        $comments = [];

        foreach ($info->comments as $comment) {
            $comment['token'] = encode_id($comment['token']);
            $comment['parent'] = encode_id($comment['parent']);
            $comment['root'] = encode_id($comment['root']);
            $comment['user']['token'] = encode_id($comment['user']['token']);
            unset($comment['id'], $comment['user']['id'], $comment['user_id']);
            $comments[$comment['token']] = $comment;
        }
        foreach ($comments as $key => $val) {
            if (decode_id($val['root']) > 0) {
//                $comments[$val['root']]['children'] = [];
//                $children = $val;
                $comments[$val['root']]['children'][] = $val;
                unset($comments[$key]);
            }
        }

        $info->comments = $comments;

        unset($info->id, $info->category->id, $info->category_id);
        return $this->success($info);
    }


}
