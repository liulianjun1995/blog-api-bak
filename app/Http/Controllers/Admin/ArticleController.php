<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleConstants;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleItem;
use App\Models\Category;
use App\Models\Post as Article;
use App\Models\Tag;
use App\Validates\ArticleValidate;
use App\Validates\TagValidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * 文章列表
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
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

    /**
     * 文章详情
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function detail(Request $request, $id)
    {
        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return $this->error('文章不存在');
        }

        $article->load(['tags' => function ($query) {
            $query->select(['tags.id', 'tags.title']);
        }]);

        $article->coverUrl = format_image($article->cover);

        return $this->success($article);
    }

    /**
     * 创建文章
     * @param Request $request
     * @param ArticleValidate $validate
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request, ArticleValidate $validate)
    {
        $params = $request->only(['title', 'abstract', 'user_id', 'category_id', 'tags', 'status', 'cover', 'content']);

        $result = $validate->store($params);

        if ($result !== true) {
            return $this->error($result);
        }

        DB::beginTransaction();

        try {
            if (!($article = Article::query()->create($params))) {
                return $this->error('创建失败');
            }
            $article->syncTags($params['tags']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->error('创建失败');
        }

        DB::commit();

        return $this->success();

    }

    /**
     * 更新文章
     * @param Request $request
     * @param $id
     * @param ArticleValidate $validate
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, $id, ArticleValidate $validate)
    {
        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return $this->error('文章不存在');
        }

        $params = $request->only(['title', 'abstract', 'user_id', 'category_id', 'status', 'cover', 'tags', 'content']);

        $result = $validate->update($id, $params);

        if ($result !== true) {
            return $this->error($result);
        }

        DB::beginTransaction();

        try {
            if ($article->fill($params)->save() === false) {
                return $this->error('更新失败');
            }
            $article->syncTags($params['tags']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            DB::rollBack();
            return $this->error('更新失败');
        }

        DB::commit();

        return $this->success();
    }

    /**
     * 修改状态
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function changeStatus(Request $request, $id): JsonResponse
    {
        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return $this->error('文章不存在');
        }

        $status = $request->post('status');

        switch ($status) {
            case 'published':
                $article->status = ArticleConstants::ARTICLE_STATUS_PUBLISHED;
                break;
            case 'draft':
                $article->status = ArticleConstants::ARTICLE_STATUS_DRAFT;
                break;
            default :
                return $this->error('参数异常');
        }

        if ($article->save() !== false) {
            return $this->success();
        }

        return $this->error();
    }

    /**
     * 获取文章分类
     * @param Request $request
     * @return JsonResponse
     */
    public function category(Request $request): JsonResponse
    {
        $data = Category::query()
            ->select(['id', 'order', 'title', 'router', 'show'])
            ->get();

        return $this->success($data);
    }

    /**
     * 图片上传
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function uploadImage(Request $request): JsonResponse
    {
        if ($file = $request->file('cover')) {
            $path = date('Y-m-d');
            if ($filepath = Storage::disk('public')->putFile($path, $file)) {
                return $this->success([
                    'url'   => Storage::disk('public')->url($filepath),
                    'path'  => $filepath
                ]);
            }
            return $this->error('上传失败');
        }

        return $this->error('参数异常');
    }

    /**
     * 删除图片
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImage(Request $request): JsonResponse
    {
        if ($image = $request->post('image')) {
            if (strpos($image, env('APP_URL')) === 0) {
                $path = substr($image, strlen(env('APP_URL'))+1+7+1);
                if (Storage::disk('public')->exists($path) && Storage::disk('public')->delete($path)) {
                    return $this->success();
                }
            }
        }

        return $this->error();
    }

    /**
     * 标签列表
     * @param Request $request
     * @return JsonResponse
     */
    public function tagList(Request $request): JsonResponse
    {
        $query = Tag::query();

        if ($keyword = $request->get('keyword')) {
            $like = '%' . $keyword . '%';
            $query->where('title', 'like', $like);
        }

        $list = $query
            ->select(['id', 'title', 'status', 'created_at'])
            ->orderBy('id', 'desc')
            ->get();

        return $this->success($list);
    }

    /**
     * 标签详情
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function tagDetail(Request $request, $id): JsonResponse
    {
        if ($id <= 0 || !($tag = Tag::query()->select(['id', 'title', 'status'])->find($id))) {
            return $this->error('标签不存在');
        }

        return $this->success($tag);
    }

    /**
     * 创建标签
     * @param Request $request
     * @param TagValidate $validate
     * @return JsonResponse
     */
    public function createTag(Request $request, TagValidate $validate): JsonResponse
    {
        $params = $request->only(['title', 'status']);

        $result = $validate->store($params);

        if ($result !== true) {
            return $this->error($result);
        }

        if (Tag::query()->create($params)) {
            return $this->success();
        }

        return $this->error('添加失败');
    }

    /**
     * 更新标签
     * @param Request $request
     * @param $id
     * @param TagValidate $validate
     * @return JsonResponse
     */
    public function updateTag(Request $request, $id, TagValidate $validate): JsonResponse
    {
        if ($id <= 0 || !($tag = Tag::query()->select(['id', 'title', 'status'])->find($id))) {
            return $this->error('标签不存在');
        }

        $params = $request->only(['title', 'status']);

        $result = $validate->update($id, $params);

        if ($result !== true) {
            return $this->error($result);
        }

        if ($tag->fill($params)->save($params)) {
            return $this->success();
        }

        return $this->error('添加失败');
    }
}
