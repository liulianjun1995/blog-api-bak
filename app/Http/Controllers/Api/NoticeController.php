<?php

namespace App\Http\Controllers\Api;

use App\Models\Notice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends ApiController
{
    public function index(Request $request, Notice $notice)
    {
        $list = $notice->query()
            ->select(['title', 'link', 'color', 'show'])
            ->orderBy('order')
            ->where('show', 1)
            ->get();

        return $this->success($list);
    }
}
