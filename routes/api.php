<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {
        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                //验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                    ->name('verificationCodes.store');
                //用户注册
                Route::post('users', 'UsersController@store')
                    ->name('user.store');
                //图片验证码
                Route::post('captchas', 'CaptchasController@store')
                    ->name('captchas.store');

                //第三方登录
                Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
                    ->where('socials_type', 'wechat')
                    ->name('socials.authorizations.store');
                //用户登录
                Route::post('authorizations', 'AuthorizationsController@store')
                    ->name('authorizations.store');
                //刷新token
                Route::put('authorizations/current', 'AuthorizationsController@update');
                //删除token
                Route::delete('authorizations/current', 'AuthorizationsController@destory');

                //某个用户详情
                Route::get('users/{user}', 'UsersController@show')
                    ->name('users.show');

                //需要登录的路由
                Route::middleware('auth:api')->group(function () {
                    //当前登录的用户信息详情
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');
                });

            });

    });


