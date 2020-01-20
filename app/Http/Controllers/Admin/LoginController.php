<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ProxyTrait;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ProxyTrait;

    /**
     * @param Request $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        $admin = Admin::query()->where('username', $username)->first();

        if ($admin === null) {
            return $this->error('管理员不存在');
        }

        if (Hash::check($password, $admin->password) === false) {
            return $this->error('密码错误');
        }

        if ($admin->status !== 1) {
            return $this->error('该账户已被禁用不存在');
        }

        $accessToken = $this->authenticate('admins');

        return $this->success($accessToken);
    }

    public function info(Request $request)
    {
        $user = $request->user('admin');

        $user->roles = ['admin'];

        return $this->success($user);
    }
}
