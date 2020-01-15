<?php

namespace App\Http\Controllers\Api;

use App\Models\Carousel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarouselController extends ApiController
{
    public function list(Request $request, Carousel $carousel)
    {
        $list = $carousel->query()
            ->select(['title', 'img', 'link'])
            ->latest()
            ->where('status', 1)
            ->take(3)
            ->get();

        $list->each(function ($item) {
            $item->img = \Storage::disk(config('admin.upload.disk'))->url($item->img);
        });

        return $this->success($list);
    }
}
