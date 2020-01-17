<?php

namespace App\Http\Controllers\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait ProxyTrait
{
    /**
     * @param string $guard
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticate($guard = '')
    {
        $client = new Client();

        try {
            $url = request()->root() . '/api/oauth/token';
            if ($guard) {
                $params = array_merge(config('passport.proxy'), [
                    'username' => request('username'),
                    'password' => request('password'),
                    'provider' => $guard,
                    'scope'    => 'blog-admin'
                ]);
            } else {
                $params = array_merge(config('passport.proxy'), [
                    'username' => request('username'),
                    'password' => request('password'),
                    'scope'    => 'blog-web'
                ]);
            }
            $respond = $client->post($url, ['form_params' => $params]);
        } catch (RequestException $exception) {
            abort(401, '请求失败，服务器错误');
        }

        if ($respond->getStatusCode() !== 401) {
            return json_decode($respond->getBody()->getContents(), true);
        }

        abort(401, '账号或密码错误');
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRefreshToken()
    {
        $client = new Client();

        try {
            $url = request()->root() . '/api/oauth/token';

            $params = array_merge(config('passport.refresh_token'), [
                'refresh_token' => request('refresh_token'),
            ]);

            $respond = $client->request('POST', $url, ['form_params' => $params]);
        } catch (RequestException $exception) {
            abort(401, '请求失败，服务器错误');
        }

        if ($respond->getStatusCode() !== 401) {
            return json_decode($respond->getBody(), true);
        }
        abort(401, '不正确的 refresh_token');

    }
}
