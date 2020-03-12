<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use  Illuminate\Support\Str;
use Gregwar\Captcha\CaptchaBuilder;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha_' . Str::random(15);
        $phone = $request->phone;

        // 生成图片验证码并设置有效时间
        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinute(200);

        // 缓存图片验证码
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateString(),
            'captcha_image_content' => $captcha->inline(),
        ];

        return response()->json($result)->setStatusCode(201);
    }
}
