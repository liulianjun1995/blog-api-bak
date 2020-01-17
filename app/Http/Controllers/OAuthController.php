<?php

namespace App\Http\Controllers;

use App\Events\UserLoginEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Socialite;

class OAuthController extends Controller
{
    public function github()
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubLogin(Request $request, User $user)
    {
        try {
            $github_user = Socialite::driver('github')->user();
            $result = $user->query()
                ->firstOrCreate(
                    [
                        'app_id' => $github_user->id,
                        'source' => 'github'
                    ],
                    [
                        'app_id'    => $github_user->id,
                        'username'  => $github_user->name,
                        'nickname'  => $github_user->nickname,
                        'name'      => $github_user->name,
                        'email'     => $github_user->email,
                        'avatar'    => $github_user->avatar,
                        'password'  => Hash::make(Str::random(10)),
                        'source'    => 'github',
                    ]
            );
            if ($result) {
                $token = 'Bearer ' . $result->createToken('Blog', ['blog-web'])->accessToken;
            }
        } catch (\Exception $e) {
//            return $e->getMessage();
            abort(500);
        }
//        event(new UserLoginEvent($result, $request));
        return redirect($request->header('referer') .'?token=' . $token);
    }

    public function qq()
    {
        return Socialite::driver('qq')->redirect();
    }

    public function qqLogin(Request $request, User $user)
    {
        try {
            $qq_user = Socialite::driver('qq')->user();
            $result = $user->query()->firstOrCreate(
                [
                    'app_id' => $qq_user->id,
                    'source' => 'qq'
                ],
                [
                    'app_id'    => $qq_user->id,
                    'username'  => $qq_user->username,
                    'nickname'  => $qq_user->nickname,
                    'name'      => $qq_user->name,
                    'email'     => $qq_user->email,
                    'avatar'    => $qq_user->avatar,
                    'password'  => Hash::make(Str::random(10)),
                    'source'    => 'qq',
                ]);
            if ($result) {
                $token = 'Bearer ' . $result->createToken('Blog')->accessToken;
            } else {
                throw new \Exception('登录异常');
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            abort(500);
//            return $e->getMessage();
        }
//        event(new UserLoginEvent($result, $request));
        return redirect(env('APP_URL') .'?token=' . $token);
    }

    public function wechat(Request $request)
    {

    }

    public function wechatLogin()
    {

    }
}
