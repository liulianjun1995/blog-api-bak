<?php

namespace App\Validates;

use App\Enums\ArticleConstants;
use Illuminate\Validation\Rule;

class TagValidate extends Validate
{
    protected $message = [
        'title.required'        => '请填写标签名称',
        'title.min'             => '标签名称至少2个字符',
        'title.max'             => '标签名称至多10个字符',
        'status.in'             => '状态参数不合法',
    ];

    /**
     * 新增验证器
     * @param array $request
     * @return bool|string
     */
    public function store($request = [])
    {
        $rules = [
            'title'         => ['required', 'min:2', 'max:10', 'unique:tags'],
            'status'        => Rule::in(array_keys(ArticleConstants::ARTICLE_TAG_STATUS))
        ];

        return $this->validate($request, $rules, $this->message);
    }

    /**
     * 更新验证器
     * @param int $id
     * @param array $request
     * @return bool|string
     */
    public function update($id = 0, $request = [])
    {
        if ($id <= 0) {
            return false;
        }

        $rules = [
            'title'         => ['required', 'min:2', 'max:10', Rule::unique('tags', 'title')->ignore($id)],
            'status'        => Rule::in(array_keys(ArticleConstants::ARTICLE_TAG_STATUS))
        ];

        return $this->validate($request, $rules, $this->message);
    }
}
