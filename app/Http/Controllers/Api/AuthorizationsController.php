<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Overtrue\LaravelSocialite\Socialite;


class AuthorizationsController extends Controller
{

    /**
     * @param $type
     * @param SocialAuthorizationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = Socialite::create($type);

        try {
            if ($code = $request->code) {
                $oauthUser = $driver->userFromCode($code);
            } else {
                //微信需要添加openid
                if ($type == 'wechat') {
                    $driver->withOpenid($request->openid);
                }
                $oauthUser = $driver->userFromToken($request->access_token);
            }
        } catch (\Exception $exception) {
            throw new AuthenticationException('参数错误,未获取到用户信息');
        }

        if (!$oauthUser->getId()) {
            throw new AuthenticationException('参数错误,未获取到用户信息');
        }

        switch ($type) {
            case 'wechat':
                $unionid = $oauthUser->getRaw()['unionid'] ?? null;
                if ($unionid) {
                    $user = User::query()->where('wechat_unionid', $unionid)->first();
                } else {
                    $user = User::query()->where('wechat_openid', $oauthUser->getId())->first();
                }
                //没有用户默认创建一个
                if (!$user) {
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'wechat_openid' => $oauthUser->getId(),
                        'wechat_unionid' => $unionid,
                    ]);
                }
                break;
        }
        $token = auth('api')->login($user);
        return $this->respondWithToken($token)->setStatusCode(201);

    }

    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;
        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            throw new AuthenticationException('账号或者密码错误');
        }

        return $this->respondWithToken($token)->setStatusCode(201);

    }

    protected function respondWithToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function update()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destory()
    {
        auth('api')->logout();
        return response(null, 204);
    }
}
