<?php

namespace App\Http\Resources;

use App\Enums\ArticleConstants;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'abstract'  => $this->abstract,
            'cover'     => $this->cover,
            'category'  => $this->category->title,
            'author'    => $this->admin->name ?? '',
            'status'    => ArticleConstants::getArticleStatusLabel($this->status),
            'view_count'=> $this->view_count,
            'is_top'    => $this->view_count,
            'is_recommend'  => $this->is_recommend,
            'created_at'    => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
