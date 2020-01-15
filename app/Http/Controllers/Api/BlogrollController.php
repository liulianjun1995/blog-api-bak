<?php

namespace App\Http\Controllers\Api;

use App\Models\Blogroll;
use Illuminate\Http\Request;

class BlogrollController extends ApiController
{
    public function index(Request $request, Blogroll $blogroll)
    {
        $list = $blogroll->query()
            ->select(['title', 'link'])
            ->orderBy('order')
            ->get();

        return $this->success($list);
    }
}
