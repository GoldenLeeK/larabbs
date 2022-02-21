<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $captchaRequest, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . Str::random(15);
        $phone = $captchaRequest->phone;

        $captcha = $captchaBuilder->build();
        $expireAt = now()->addMinutes(2);
        Cache::put($key, [
            'phone' => $phone,
            'code' => $captchaBuilder->getPhrase()
        ], $expireAt);

        return response()->json([
            'captcha_key' => $key,
            'expire_at' => $expireAt,
            'captcha_img_content' => $captcha->inline(),
        ])->setStatusCode(201);

    }
}
