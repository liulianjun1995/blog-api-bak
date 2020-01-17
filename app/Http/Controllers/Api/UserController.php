<?php

namespace App\Http\Controllers\Api;

use App\Models\Comments;
use App\Models\PostPraise;
use App\Models\Post;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function user()
    {
        $user = \Auth::user()->only(['nickname', 'avatar']);
        return $this->success($user);
    }

    public function comment(Request $request, Post $posts, $token)
    {
        $post_id = decode_id($token);

        if ($post_id <= 0 || !($post = $posts->query()->find($post_id))) {
            return $this->error('文章不存在！');
        }
        if (!($content = trim($request->input('content')))) {
            return $this->error('请输入评论内容');
        }
        if ($comment = $post->comments()->create([
            'user_id'   => \Auth::id(),
            'content'   => $content,
            'parent_id'    => 0,
            'root_id'    => 0,
        ])) {
            return $this->success([
                'token'     => encode_id($comment->id),
                'parent'    => encode_id($comment->parent_id),
                'content'   => $comment->content,
                'root'      => encode_id($comment->root_id),
                'user'      => [
                    'token'     => encode_id(\Auth::id()),
                    'avatar'    => \Auth::user()->avatar,
                    'nickname'  => \Auth::user()->nickname,
                ],
                'created_at'    => $comment->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return $this->error('系统异常');
    }

    public function reply(Request $request, Comments $comments, $token)
    {
        $comment_id = decode_id($token);
        if ($comment_id <=0 || !($comment = $comments->query()->find($comment_id))) {
            return $this->error('评论不存在！');
        }
        if (!($content = trim($request->input('content')))) {
            return $this->error('请输入评论内容');
        }
        $reply = $comments->query()->create([
            'parent_id' => $comment_id,
            'content'   => htmlspecialchars($content),
            'user_id'   => \Auth::id(),
            'root_id'   => $comment->root_id ?:  $comment->id,
            'post_id'   => $comment->post_id
        ]);
        return $this->success([
            'token'     => encode_id($reply->id),
            'parent'    => encode_id($reply->parent_id),
            'content'   => $reply->content,
            'root'      => encode_id($reply->root_id),
            'user'      => [
                'token'     => encode_id(\Auth::id()),
                'avatar'    => \Auth::user()->avatar,
                'nickname'  => \Auth::user()->nickname,
            ],
            'created_at'    => $reply->created_at
        ]);
    }

    public function praise(Request $request, Post $posts, $token)
    {
        $id = decode_id($token);
        if ($id <= 0 || !($post = $posts->query()->find($id))) {
            return $this->error('文章不存在');
        }
        if (PostPraise::query()->where('user_id', \Auth::id())->where('post_id', $id)->exists()) {
            return $this->error('你已点过赞了');
        }
        if ($post->praises()->create([
            'user_id'   => \Auth::id(),
            'ip'        => $request->ip(),
        ])) {
            return $this->success();
        }
        return $this->error('系统异常');
    }
}
