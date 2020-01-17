<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends AdminController
{
    public function list(Request $request)
    {
        $query = Post::query();

        $list = $query->paginate(15);

        return $this->page($list);
    }
}
