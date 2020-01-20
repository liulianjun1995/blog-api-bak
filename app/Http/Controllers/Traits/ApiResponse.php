<?php

namespace App\Http\Controllers\Traits;

use App\Http\Resources\BaseItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * 创建响应信息
     * @param bool $code
     * @param array $respond_data
     * @param string $message
     * @return array
     */
    protected function respond($code = 20001, $respond_data = [], $message = '') : array
    {
        return ['code' => $code, 'data' => $respond_data, 'message' => $message];
    }

    /**
     * 成功
     * @param array $respond_data
     * @param string $message
     * @param bool $code
     * @return array
     */
    protected function success($respond_data = [], string $message = 'success', $code = 20000) : array
    {
        return $this->respond($code, $respond_data, $message);
    }

    /**
     * 失败
     * @param string $message
     * @param array $respond_data
     * @param bool $code
     * @return array
     */
    protected function error(string $message = 'error', $respond_data = [], $code = 20001) : array
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
