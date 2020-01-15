<?php

namespace App\Http\Controllers\Api;

use App\Models\Comments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends ApiController
{
    public function new(Request $request, Comments $comments)
    {
        $field = ['id', 'id as token', 'post_id as post', 'user_id', 'content'];

        $list = $comments->query()
            ->where('show', 1)
            ->select($field)
            ->with(['user:id,nickname,avatar'])
            ->latest()
            ->take(10)
            ->get();

        $list->each(function ($item) {
            $item->token = encode_id($item->token);
            $item->post = encode_id($item->post);
            $item->user->token = encode_id($item->user->id);
            unset($item->id, $item->user_id, $item->user->id);
        });

        return $this->success($list);
    }
}
