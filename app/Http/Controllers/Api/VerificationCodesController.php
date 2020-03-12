<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodesRequeset;
use Overtrue\EasySms\EasySms;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodesRequeset $request, Easysms $easysms)
    {
        $phone = $request->phone;

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成思维随机数,左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easysms->send($phone, [
                    'template' => config('easysms.gatewaty.aliyu.template.register'),
                    'data' => ['code' => $code,],
                ]);
            } catch (\Overtrue\Easysms\Exception\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $key = 'verificationCodes_' . Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码5分钟过期
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        return response()->json([
            'key' => $key,
            'expeiredAt' => $expiredAt->toDateString(),
        ])->setStatusCode(201);
    }
}
