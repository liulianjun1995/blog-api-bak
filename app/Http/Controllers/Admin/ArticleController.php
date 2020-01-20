<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleItem;
use App\Models\Category;
use App\Models\Post as Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function list(Request $request)
    {
        $query = Article::query();

        if ($category = $request->get('category')) {
            $query->where('category_id', $category);
        }

        $list = $query
            ->orderBy('id', 'desc')
            ->paginate(15);

        return $this->page($list, ArticleItem::class);
    }

    public function detail(Request $request, $id)
    {
        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return $this->error('文章不存在');
        }

        return $this->success($article);
    }

    public function category(Request $request)
    {
        $data = Category::query()
            ->select(['id', 'order', 'title', 'router', 'show'])
            ->get();

        return $this->success($data);
    }
}
