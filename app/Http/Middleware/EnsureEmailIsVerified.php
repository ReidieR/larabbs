<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 三个判断
        // 1.如果用户登录
        // 2.并且没有验证邮箱
        // 3.并且访问不是email 验证相关的 URL 获取退出 URL
        if (
            $request->user() && !$request->user()->hasVerifiedEmail()
            && !$request->is('email/*', 'logout')
        ) {
            // 根据客户端返回对应的内容
            return $request->expectsJson()
                ? abort(403, 'Your eamil address is not verified')
                : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
