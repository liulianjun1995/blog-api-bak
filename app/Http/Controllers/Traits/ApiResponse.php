<?php

namespace App\Http\Controllers\Traits;

use App\Http\Resources\BaseItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * 创建响应信息
     * @param int $code
     * @param array $respond_data
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($code = 20001, $respond_data = [], $message = '') : JsonResponse
    {
        return response()->json([
            'code' => $code,
            'data' => $respond_data,
            'message' => $message]
        );
    }

    /**
     * 成功
     * @param array $respond_data
     * @param string $message
     * @param bool $code
     * @return JsonResponse
     */
    protected function success($respond_data = [], string $message = 'success', $code = 20000) : JsonResponse
    {
        return $this->respond($code, $respond_data, $message);
    }

    /**
     * 失败
     * @param string $message
     * @param array $respond_data
     * @param bool $code
     * @return JsonResponse
     */
    protected function error(string $message = 'error', $respond_data = [], $code = 20001) : JsonResponse
    {
        $this->error = trim($message);
        return $this->respond($code, $respond_data, $message);
    }

    /**
     * 分页
     * @param LengthAwarePaginator $paginator
     * @param string $class
     * @return \Illuminate\Http\JsonResponse
     */
    protected function page(LengthAwarePaginator $paginator, $class = BaseItem::class)
    {
        $items = [];

        foreach($paginator->items() as $item) {
            $items[] = new $class($item);
        }

        $res = [
            'code'          => 20000,
            'items'         => $items,
            'pageSize'      => $paginator->perPage(),
            'currentPage'   =>$paginator->currentPage(),
            'total'         => $paginator->total(),
            'totalPage'     => $paginator->lastPage()
        ];

        return response()->json($res);
    }
}
