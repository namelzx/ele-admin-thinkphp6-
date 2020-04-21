<?php
declare (strict_types=1);

namespace app\http\middleware;


namespace app\http\middleware;

use app\services\TokenService;


class CheckLogin
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //
        $res = TokenService::GTadmimScope($request->header('x-token')); //进行权限验证
        if ($res == true) {
            return $next($request);
        }
        return app('json')->fail('没有权限');
    }
}
