<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function options(Request $request)
    {
        $query = Admin::query();

        $list = $query->select(['id', 'name'])->get();

        return $this->success($list);
    }
}
